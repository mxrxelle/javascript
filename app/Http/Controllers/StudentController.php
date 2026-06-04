<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCode;
use App\Models\StudentCourse;

class StudentController extends Controller
{
    public function dashboard()
    {
        $studentCourses = StudentCourse::with('course')
            ->where('user_id', Auth::id())
            ->get();

        return view('student.userdashboard', compact('studentCourses'));
    }

    public function activateCourse(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $courseCode = CourseCode::where('code', $request->code)->first();

        if (!$courseCode) {
            return back()->with('error', 'Invalid activation code.');
        }

        $course = $courseCode->course;
        if (!$course || $course->status !== 'approved' || !$course->is_active) {
            return back()->with('error', 'This course is currently not available.');
        }

        $alreadyActivated = StudentCourse::where('user_id', Auth::id())
            ->where('course_id', $courseCode->course_id)
            ->exists();

        if ($alreadyActivated) {
            return back()->with('error', 'You already activated this course.');
        }

        StudentCourse::create([
            'user_id' => Auth::id(),
            'course_id' => $courseCode->course_id,
            'progress' => 0,
        ]);

        return back()->with('success', 'Course activated successfully!');
    }

    public function courseViewer(Course $course)
    {
        $isActivated = StudentCourse::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$isActivated || $course->status !== 'approved' || !$course->is_active) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This course is currently not available.');
        }

        $course->load([
            'modules.lessons'
        ]);

        return view('student.courseviewer', compact('course'));
    }
}