<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function dashboard()
    {
        return view('student.userdashboard');
    }

    public function courseViewer()
    {
        return view('student.courseviewer');
    }
}