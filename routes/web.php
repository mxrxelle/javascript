<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Landing page redirection
Route::get('/', function () {
    return view('dashboard');
})->name('landing');

// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    if ($request->user()->role === 'admin') {
        return redirect('/admin/dashboard');
    }

    if ($request->user()->role === 'teacher') {
        return redirect('/teacher/dashboard');
    }

    return redirect('/student/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// Dashboards Protected by Auth and Roles
Route::middleware(['auth'])->group(function () {

    Route::get('/api/courses/status-check', [AdminController::class, 'coursesStatusCheck'])
        ->name('courses.statusCheck');

    // Student Dashboard
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
            ->name('student.dashboard');

        Route::post('/student/activate-course', [StudentController::class, 'activateCourse'])
            ->name('student.activateCourse');

        Route::get('/student/courseviewer/{course}', [StudentController::class, 'courseViewer'])
            ->name('student.courseviewer');

        Route::post('/student/courseviewer/{course}/complete-lesson', [StudentController::class, 'completeLesson'])
            ->name('student.courseviewer.completeLesson');

        Route::post('/student/courseviewer/{course}/submit-quiz', [StudentController::class, 'submitQuiz'])
            ->name('student.courseviewer.submitQuiz');
    });

    // Teacher Dashboard & Course Management
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
            ->name('teacher.dashboard');
        
        Route::get('/teacher/courses/create', [TeacherController::class, 'create'])
            ->name('teacher.courses.create');
            
        Route::get('/teacher/courses/{course}/edit', [TeacherController::class, 'edit'])
            ->name('teacher.courses.edit');
            
        Route::post('/teacher/courses/store', [TeacherController::class, 'store'])
            ->name('teacher.courses.store');
            
        Route::post('/teacher/courses/upload', [TeacherController::class, 'upload'])
            ->name('teacher.courses.upload');
            
        Route::post('/teacher/courses/{course}/toggle', [TeacherController::class, 'toggleStatus'])
            ->name('teacher.courses.toggleStatus');
            
        Route::get('/teacher/courses/{course}/details', [TeacherController::class, 'showDetails'])
            ->name('teacher.courses.details');

        Route::get('/teacher/submissions', [TeacherController::class, 'submissions'])
            ->name('teacher.submissions');

        Route::get('/teacher/analytics', [TeacherController::class, 'analytics'])
            ->name('teacher.analytics');

        // Courses Master Directory
        Route::get('/teacher/courses', [TeacherController::class, 'coursesIndex'])
            ->name('teacher.courses.index');

        // View vouchers for a course
        Route::get('/teacher/courses/{course}/vouchers', [TeacherController::class, 'courseVouchers'])
            ->name('teacher.courses.vouchers');

        // View student progress for a course
        Route::get('/teacher/courses/{course}/students', [TeacherController::class, 'courseStudents'])
            ->name('teacher.courses.students');

        // View student quiz attempts for a course
        Route::get('/teacher/courses/{course}/students/{student}/quiz-attempts', [TeacherController::class, 'studentQuizAttempts'])
            ->name('teacher.courses.student.quiz-attempts');

        // Unlock quiz for a student
        Route::post('/teacher/courses/{course}/unlock-quiz/{lesson}/{student}', [TeacherController::class, 'unlockQuiz'])
            ->name('teacher.courses.unlockQuiz');
    });

    // Admin Dashboard Group
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/admin/approvals', [AdminController::class, 'approvalsHub'])
            ->name('admin.approvals');

        Route::post('/admin/courses/{id}/approve', [AdminController::class, 'approveCourse'])
            ->name('admin.courses.approve');

        Route::post('/admin/courses/{id}/reject', [AdminController::class, 'rejectCourse'])
            ->name('admin.courses.reject');

        Route::get('/admin/users', [AdminController::class, 'userManagement'])
            ->name('admin.users');

        Route::get('/admin/facilitators', [AdminController::class, 'facilitatorManagement'])
            ->name('admin.facilitators');

        Route::post('/admin/store-teacher', [AdminController::class, 'storeTeacher'])
            ->name('admin.storeTeacher');

        Route::post('/admin/facilitators/{id}/resend', [AdminController::class, 'resendInvite'])
            ->name('admin.facilitators.resend');

        Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])
            ->name('admin.users.update');

        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])
            ->name('admin.users.delete');
    });
});