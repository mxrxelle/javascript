<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered; 
use App\Notifications\SendOtpVerification;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        // Kapag naka-login na, huwag nang papasukin sa register form
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }
        return view('auth.register');
    }

    // Handle the registration request
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
            'affiliation' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        // 1. Gumawa ng random 6-digit code
        $otpCode = rand(100000, 999990);

        // 2. I-save ang Student kasama ang code
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'birthday' => $request->birthday,
            'affiliation' => $request->affiliation,
            'contact_number' => $request->contact_number,
            'verification_code' => $otpCode, // Isave ang otp code
        ]);

        // 3. I-send ang OTP Notification sa email ng user
        $user->notify(new SendOtpVerification($otpCode));

        // 4. I-login muna natin sila para mapunta sila sa verify control screen
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}