<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    // Show the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handle the registration request
    public function register(Request $request)
    {
        // 1. Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => 'required|date',
            'affiliation' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ]);

        // 2. Create the Student
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student', // Default role
            'birthday' => $request->birthday,
            'affiliation' => $request->affiliation,
            'contact_number' => $request->contact_number,
        ]);

        // 3. Log them in
        Auth::login($user);

        // Auth::login($user); <-- I-comment out o burahin ito para hindi mag-auto login

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
}