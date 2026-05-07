<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;


// Registration Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Login Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Dashboards (Protektado ng Middleware)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Student Dashboard (Role: Student lang dapat)
    Route::middleware(['role:student'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard'); // Dapat may dashboard.blade.php ka na dito
        })->name('dashboard');
    });

    // Teacher Dashboard (Role: Teacher lang dapat)
    Route::middleware(['role:teacher'])->group(function () {
        Route::get('/teacher/dashboard', function () {
            return view('teacher.dashboard'); // Dapat may teacher/dashboard.blade.php ka
        })->name('teacher.dashboard');
    });

    // Admin Dashboard & Functionalities
    Route::middleware(['role:admin'])->group(function () {
        // Pinalitan natin 'to para dumaan sa Controller
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Dagdag na route para sa pag-save ng Teacher account
        Route::post('/admin/add-teacher', [AdminController::class, 'storeTeacher'])->name('admin.storeTeacher');
    });
});

//  Verification Notice (Ang page na makikita nila kung 'di pa sila verified)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

//  Verification Handler (Ang mag-o-update sa database pag-click ng link sa email)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

//  Resend Verification Email logic
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');