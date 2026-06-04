<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

$keepStudent = 'thessalonicamary.ona1@gmail.com';
$keepAdmin = 'admin@certly.com';
$keepTeacher = 'teacher@example.com';
$keepCourseTitle = 'awitt';

DB::transaction(function () use ($keepStudent, $keepAdmin, $keepTeacher, $keepCourseTitle) {
    // 1. Delete Students
    $studentsDeleted = User::where('role', 'student')->where('email', '!=', $keepStudent)->delete();
    echo "Deleted $studentsDeleted dummy student accounts.\n";

    // 2. Delete Admins
    $adminsDeleted = User::where('role', 'admin')->where('email', '!=', $keepAdmin)->delete();
    echo "Deleted $adminsDeleted dummy admin accounts.\n";

    // 3. Delete Teachers
    $teachersDeleted = User::where('role', 'teacher')->where('email', '!=', $keepTeacher)->delete();
    echo "Deleted $teachersDeleted dummy teacher accounts.\n";

    // 4. Delete Courses
    $coursesDeleted = Course::where('title', '!=', $keepCourseTitle)->delete();
    echo "Deleted $coursesDeleted dummy courses.\n";
});

echo "\nDatabase cleanup completed successfully!\n";
