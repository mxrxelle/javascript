<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with registration summary counters.
     */
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();

        $recentInvitations = User::where('role', 'teacher')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('teachers', 'students', 'recentInvitations'));
    }

    /**
     * Auto-generate facilitator credentials with 10-char alphanumeric random password.
     */
    public function storeTeacher(Request $request)
    {
        // PANGHULI NG DUPLICATE EMAIL: Kung gamit na, babalik sa page dala ang error text
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ], [
            'email.unique' => 'This email is already registered.'
        ]);

        $username = explode('@', $request->email)[0];
        $generatedName = ucfirst($username);
        
        // Random 10 alphanumeric characters
        $temporaryPassword = Str::random(10); 

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bautistamarielle1226@gmail.com';
            $mail->Password   = 'plgk qwzx fjgd obcb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bautistamarielle1226@gmail.com', 'Certly Platform');
            $mail->addAddress($request->email, $generatedName);

            $mail->isHTML(true);
            $mail->Subject = 'Invitation to Join Certly as a Facilitator';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #002855;'>
                    <h2 style='color: #002855;'>Welcome to Certly, {$generatedName}!</h2>
                    <p>You have been invited by the Administrator to join our platform as a <strong>Facilitator/Teacher</strong>.</p>
                    <p>You can now log in and start creating your courses using the temporary credentials below:</p>
                    <div style='background-color: #f8fafc; padding: 15px; border-left: 4px solid #ffca28; margin: 20px 0;'>
                        <strong>Email:</strong> {$request->email}<br>
                        <strong>Temporary Password:</strong> <span style='color: #ff4d4d; font-weight: bold; font-family: monospace; font-size: 16px;'>{$temporaryPassword}</span>
                    </div>
                    <p style='font-size: 13px; color: #cbd5e1;'>Please change your password immediately upon your first login for security purposes.</p>
                    <br>
                    <p>Best regards,<br><strong>Certly Admin Team</strong></p>
                </div>
            ";

            $mail->send();

            User::create([
                'name' => $generatedName,
                'email' => $request->email,
                'password' => Hash::make($temporaryPassword),
                'role' => 'teacher',
                'birthday' => '2000-01-01',
                'affiliation' => 'LMS Faculty',
                'contact_number' => 'N/A',
            ]);

            return redirect()->back()
                ->with('success', "Invitation successfully sent to {$request->email}! Facilitator account created.");

        } catch (Exception $e) {
            return back()->withErrors(['email' => "Mail could not be sent. Mailer Error: {$mail->ErrorInfo}"])->withInput();
        }
    }

    /**
     * Resend invitation and update with a NEW random password setup.
     */
    public function resendInvite($id)
    {
        $user = User::findOrFail($id);
        
        // Bagong random password para sa security reset
        $temporaryPassword = Str::random(10); 

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'bautistamarielle1226@gmail.com';
            $mail->Password   = 'plgk qwzx fjgd obcb';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('bautistamarielle1226@gmail.com', 'Certly Platform');
            $mail->addAddress($user->email, $user->name);

            $mail->isHTML(true);
            $mail->Subject = 'REMINDER: Invitation to Join Certly as a Facilitator';
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #002855;'>
                    <h2 style='color: #002855;'>Hello, {$user->name}!</h2>
                    <p>This is a quick reminder that you have a pending invitation to join Certly as a <strong>Facilitator</strong>.</p>
                    <p>Use your newly updated temporary login parameters displayed down below:</p>
                    <div style='background-color: #f8fafc; padding: 15px; border-left: 4px solid #ffca28; margin: 20px 0;'>
                        <strong>Email:</strong> {$user->email}<br>
                        <strong>New Temporary Password:</strong> <span style='color: #ff4d4d; font-weight: bold; font-family: monospace; font-size: 16px;'>{$temporaryPassword}</span>
                    </div>
                    <p style='font-size: 13px; color: #cbd5e1;'>Please use this new password as your old temporary password has been overridden.</p>
                    <br>
                    <p>Best regards,<br><strong>Certly Admin Team</strong></p>
                </div>
            ";

            $mail->send();

            $user->update([
                'password' => Hash::make($temporaryPassword)
            ]);

            return redirect()->back()->with('success', "New random password generated and sent to {$user->email}!");

        } catch (Exception $e) {
            return redirect()->back()->with('error', "Failed to re-send mail. Error: {$mail->ErrorInfo}");
        }
    }

    /**
     * Manage facilitators exclusively with tracking statuses.
     */
    public function facilitatorManagement(Request $request)
    {
        $search = $request->input('search');

        $totalFacilitators = User::where('role', 'teacher')->count();
        $query = User::where('role', 'teacher');

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $facilitators = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // CHECKING NG STATUS: Ginagamit ang custom variables para sa view layer
        foreach ($facilitators as $fac) {
            if (is_null($fac->verified_at) && is_null($fac->email_verified_at)) {
                $fac->status_label = 'Pending';
                $fac->status_class = 'bg-secondary text-white';
            } else {
                $fac->status_label = 'Active';
                $fac->status_class = 'bg-success text-success-subtle text-success';
            }

            // Placeholder counter (Feature 3)
            $fac->courses_count = 0; 
        }

        return view('admin.facilitators', compact('facilitators', 'totalFacilitators'));
    }

    /**
     * Render the Approvals Hub workspace layout context.
     */
    public function approvalsHub()
    {
        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        $pendingCourses = collect([]); 

        return view('admin.approvals', compact('teachers', 'students', 'pendingCourses'));
    }

    /**
     * Render the core User Management directory with active dynamic filters.
     */
    public function userManagement(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        $teachers = User::where('role', 'teacher')->get();
        $students = User::where('role', 'student')->get();
        $totalCount = User::count();

        $query = User::query();

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($role)) {
            $query->where('role', $role);
        }

        $allUsers = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.users', compact('allUsers', 'teachers', 'students', 'totalCount'));
    }

    /**
     * Update an existing user's entry attributes block.
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,teacher,student',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'Account updated successfully!');
    }

    /**
     * Delete an active user identity framework entry record.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Account deleted successfully!');
    }
}