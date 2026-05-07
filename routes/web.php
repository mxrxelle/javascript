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
Route::middleware(['auth'])->group(function () {
    
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

