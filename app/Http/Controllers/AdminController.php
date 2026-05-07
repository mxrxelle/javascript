<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Show the list of teachers and the form to add one
    public function index()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.dashboard', compact('teachers'));
    }

    // Save the new teacher to the database
    public function storeTeacher(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher', // Set the role manually to teacher
            'birthday' => '2000-01-01', // Placeholder or add to form
            'affiliation' => 'LMS Faculty',
            'contact_number' => 'N/A',
        ]);

        return back()->with('success', 'Teacher added successfully!');
    }
}