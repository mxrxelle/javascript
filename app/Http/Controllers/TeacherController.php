<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        // Kukunin lahat ng 'student' para makita ng Staff/Teacher
        $students = User::where('role', 'student')->get();
        
        return view('teacher.dashboard', compact('students'));
    }
}