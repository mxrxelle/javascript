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

    // Student Dashboard
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', [StudentController::class, 'dashboard'])
            ->name('student.dashboard');

        Route::post('/student/activate-course', [StudentController::class, 'activateCourse'])
            ->name('student.activateCourse');

        Route::get('/student/courseviewer/{course}', [StudentController::class, 'courseViewer'])
            ->name('student.courseviewer');
    });

    // Teacher Dashboard
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher/dashboard', [TeacherController::class, 'index'])
            ->name('teacher.dashboard');
    });

    // Admin Dashboard Group
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])
            ->name('admin.dashboard');

        Route::get('/admin/approvals', [AdminController::class, 'approvalsHub'])
            ->name('admin.approvals');

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