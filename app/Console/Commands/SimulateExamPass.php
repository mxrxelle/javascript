<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\FinalExamAttempt;
use App\Mail\CertificateIssued;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SimulateExamPass extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'exam:simulate-pass
                            {student_id : The ID of the student user}
                            {course_id  : The ID of the course}
                            {--reset    : Delete any existing attempt first so you can re-run}';

    /**
     * The console command description.
     */
    protected $description = 'Simulate a PASSED final exam for a student (testing only). Generates the PDF certificate and sends the email.';

    public function handle(): int
    {
        // ── 1. Resolve student & course ────────────────────────────────────
        $student = User::find($this->argument('student_id'));
        if (!$student) {
            $this->error("Student with ID {$this->argument('student_id')} not found.");
            return self::FAILURE;
        }

        $course = Course::with('finalExam')->find($this->argument('course_id'));
        if (!$course) {
            $this->error("Course with ID {$this->argument('course_id')} not found.");
            return self::FAILURE;
        }

        $this->info("👤  Student : {$student->name} ({$student->email})");
        $this->info("📚  Course  : {$course->title}");
        $this->newLine();

        // ── 2. Optionally clear existing attempt ──────────────────────────
        $existing = FinalExamAttempt::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            if ($this->option('reset')) {
                $existing->answers()->delete();
                $existing->delete();
                $this->warn("⚠  Existing attempt deleted (--reset flag used).");
            } else {
                $this->error("An attempt already exists for this student+course.");
                $this->line("  Run again with --reset to delete it first:");
                $this->line("  php artisan exam:simulate-pass {$student->id} {$course->id} --reset");
                return self::FAILURE;
            }
        }

        // Also remove any existing certificate
        Certificate::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->delete();

        // ── 3. Create a simulated PASSED attempt ──────────────────────────
        FinalExamAttempt::create([
            'student_id'    => $student->id,
            'course_id'     => $course->id,
            'score'         => 100,
            'passed'        => true,
            'correct_count' => 40,
            'submitted_at'  => now(),
        ]);

        $this->info("✅  Simulated exam attempt created (score: 100%, passed: true)");

        // ── 4. Generate Certificate ────────────────────────────────────────
        $certificateUid = 'CERTLY-' . date('Y') . '-' . strtoupper(Str::random(8));

        $certificate = Certificate::create([
            'student_id'      => $student->id,
            'course_id'       => $course->id,
            'certificate_uid' => $certificateUid,
            'file_path'       => 'certificates/' . $certificateUid . '.pdf',
            'issued_at'       => now(),
        ]);

        $this->info("🏆  Certificate record created: {$certificateUid}");

        // Generate the PDF
        $pdf = Pdf::loadView('pdf.certificate', [
            'student'     => $student,
            'course'      => $course,
            'certificate' => $certificate,
            'date'        => now()->format('F j, Y'),
        ])->setPaper('a4', 'landscape');

        $certDir = storage_path('app/public/certificates');
        if (!is_dir($certDir)) {
            mkdir($certDir, 0755, true);
        }

        $pdfPath = $certDir . '/' . $certificateUid . '.pdf';
        $pdf->save($pdfPath);

        $this->info("📄  PDF saved to: storage/app/public/certificates/{$certificateUid}.pdf");
        $this->info("🌐  Public URL  : /storage/certificates/{$certificateUid}.pdf");

        // ── 5. Send Email ──────────────────────────────────────────────────
        if ($this->confirm("📧  Send certificate email to {$student->email}?", true)) {
            try {
                Mail::to($student->email)->send(new CertificateIssued($student, $course, $certificate, $pdfPath));
                $this->info("✉️   Email sent successfully to {$student->email}!");
            } catch (\Exception $e) {
                $this->warn("⚠  Email failed: " . $e->getMessage());
            }
        } else {
            $this->line("   Email skipped.");
        }

        $this->newLine();
        $this->info("🎉  Done! The student can now see their certificate on the dashboard.");
        $this->info("    Student Dashboard → My Certificates → Download Certificate");

        return self::SUCCESS;
    }
}
