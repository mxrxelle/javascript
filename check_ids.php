<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = 'thessalonicamary.ona1@gmail.com';
$courseId = 20;

$student = \App\Models\User::where('email', $email)->first();
if (!$student) {
    die("Student not found!\n");
}

echo "Student ID: {$student->id} | Name: {$student->name} | Email: {$student->email}\n";

// Check if enrolled in course 20
$enrolled = \App\Models\StudentCourse::where('user_id', $student->id)
    ->where('course_id', $courseId)
    ->first();

if ($enrolled) {
    echo "Already enrolled! Progress: {$enrolled->progress}%\n";
} else {
    // Enroll with 100% progress
    \App\Models\StudentCourse::create([
        'user_id'   => $student->id,
        'course_id' => $courseId,
        'progress'  => 100,
    ]);
    echo "Enrolled in course ID:{$courseId} with 100% progress.\n";
}
