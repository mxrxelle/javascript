<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\CourseCode;
use App\Models\StudentCourse;
use App\Models\StudentLessonProgress;
use App\Models\StudentProgress;
use App\Models\Lesson;
use App\Models\QuizQuestion;
use App\Models\QuizChoice;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptQuestion;

class StudentController extends Controller
{
    /**
     * Show the student dashboard with their activated courses
     */
    public function dashboard()
    {
        $studentCourses = StudentCourse::with('course')
            ->where('user_id', Auth::id())
            ->get();

        return view('student.userdashboard', compact('studentCourses'));
    }

    /**
     * Activate a course using a code
     */
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
            'user_id'   => Auth::id(),
            'course_id' => $courseCode->course_id,
            'progress'  => 0,
        ]);

        return back()->with('success', 'Course activated successfully!');
    }

    /**
     * View a course along with modules, lessons, PDFs, and YouTube videos.
     * Passes quiz attempt counts so the frontend can render locked states on load.
     */
    public function courseViewer(Course $course)
    {
        $studentId = Auth::id();

        $isActivated = StudentCourse::where('user_id', $studentId)
            ->where('course_id', $course->id)
            ->exists();

        if (!$isActivated || $course->status !== 'approved' || !$course->is_active) {
            return redirect()->route('student.dashboard')
                ->with('error', 'This course is currently not available.');
        }

        // Load modules and lessons
        $course->load([
            'modules' => function ($query) {
                $query->orderBy('sort_order');
            },
            'modules.lessons' => function ($query) {
                $query->orderBy('sort_order');
            },
            'modules.lessons.files'
        ]);

        // Get completed lessons for this student
        $completedLessonIds = StudentProgress::where('student_id', $studentId)
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->toArray();

        // Sort lessons inside each module
        foreach ($course->modules as $module) {
            $sortedLessons = $module->lessons->sortBy(function ($lesson) {
                if (in_array($lesson->type, ['pdf', 'presentation', 'reading'])) return 1;
                elseif ($lesson->type === 'video') return 2;
                elseif ($lesson->type === 'quiz') return 3;
                return 4;
            })->values();
            $module->setRelation('lessons', $sortedLessons);
        }

        // Collect all lesson IDs for this course
        $allLessonIds = $course->modules->flatMap(fn($m) => $m->lessons->pluck('id'))->toArray();

        // Build quiz attempt summary per lesson: { lessonId => { total, best_score, ever_passed } }
        $quizAttemptData = [];
        if (!empty($allLessonIds)) {
            $attemptRows = QuizAttempt::where('student_id', $studentId)
                ->whereIn('lesson_id', $allLessonIds)
                ->selectRaw('lesson_id, COUNT(*) as total, MAX(score) as best_score, MAX(CAST(passed AS UNSIGNED)) as ever_passed')
                ->groupBy('lesson_id')
                ->get();

            foreach ($attemptRows as $row) {
                $quizAttemptData[$row->lesson_id] = [
                    'total'       => (int) $row->total,
                    'best_score'  => (int) $row->best_score,
                    'ever_passed' => (bool) $row->ever_passed,
                ];
            }
        }

        return view('student.courseviewer', compact('course', 'completedLessonIds', 'quizAttemptData'));
    }

    /**
     * Return quiz attempt status for a single lesson (used for checking lock status)
     */
    public function quizStatus(Lesson $lesson)
    {
        $studentId = Auth::id();
        $attempts = QuizAttempt::where('student_id', $studentId)
            ->where('lesson_id', $lesson->id)
            ->orderBy('attempt_number')
            ->get();

        $total      = $attempts->count();
        $bestScore  = $attempts->max('score') ?? 0;
        $everPassed = $attempts->where('passed', true)->isNotEmpty();

        return response()->json([
            'total'       => $total,
            'best_score'  => $bestScore,
            'ever_passed' => $everPassed,
            'locked'      => $total >= 3,
        ]);
    }

    /**
     * Mark a lesson as completed
     */
    public function completeLesson(Request $request, Course $course)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
        ]);

        $studentId = Auth::id();
        $lessonId  = $request->lesson_id;

        StudentProgress::updateOrCreate(
            ['student_id' => $studentId, 'lesson_id' => $lessonId],
            ['completed_at' => now()]
        );

        StudentLessonProgress::updateOrCreate(
            ['user_id' => $studentId, 'lesson_id' => $lessonId],
            ['completed' => true]
        );

        $this->updateCourseProgress($studentId, $course);

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as completed successfully.'
        ]);
    }

    /**
     * Grade and submit a quiz — enforces 3-attempt maximum
     */
    public function submitQuiz(Request $request, Course $course)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'answers'   => 'nullable|array',
        ]);

        $studentId  = Auth::id();
        $lessonId   = $request->lesson_id;
        $submittedAnswers = $request->answers ?? [];

        // ── Attempt gate ─────────────────────────────────────────────────────
        $existingAttemptCount = QuizAttempt::where('student_id', $studentId)
            ->where('lesson_id', $lessonId)
            ->count();

        if ($existingAttemptCount >= 3) {
            return response()->json([
                'success' => false,
                'locked'  => true,
                'error'   => 'Maximum attempts reached. This quiz is locked.',
            ], 403);
        }
        // ─────────────────────────────────────────────────────────────────────

        // Load only the submitted questions
        $submittedQuestionIds = array_map('intval', array_keys($submittedAnswers));
        if (!empty($submittedQuestionIds)) {
            $questions = QuizQuestion::with('choices')
                ->where('lesson_id', $lessonId)
                ->whereIn('id', $submittedQuestionIds)
                ->get();
        } else {
            $questions = QuizQuestion::with('choices')->where('lesson_id', $lessonId)->get();
        }

        if ($questions->isEmpty()) {
            // No questions — auto-pass
            StudentProgress::updateOrCreate(
                ['student_id' => $studentId, 'lesson_id' => $lessonId],
                ['completed_at' => now()]
            );
            StudentLessonProgress::updateOrCreate(
                ['user_id' => $studentId, 'lesson_id' => $lessonId],
                ['completed' => true]
            );
            $this->updateCourseProgress($studentId, $course);

            return response()->json([
                'success'        => true,
                'passed'         => true,
                'score'          => 0,
                'total'          => 0,
                'percentage'     => 100,
                'attempt_number' => $existingAttemptCount + 1,
                'attempts_used'  => $existingAttemptCount + 1,
                'max_attempts'   => 3,
                'best_score'     => 100,
            ]);
        }

        $correctCount = 0;
        $feedback     = [];

        foreach ($questions as $question) {
            $correctChoice   = $question->choices->firstWhere('is_correct', true);
            $correctChoiceId = $correctChoice ? $correctChoice->id : null;
            $submittedChoiceId = isset($submittedAnswers[$question->id])
                ? (int) $submittedAnswers[$question->id]
                : null;

            $isCorrect = ($correctChoiceId !== null && $submittedChoiceId === $correctChoiceId);
            if ($isCorrect) $correctCount++;

            $feedback[$question->id] = [
                'is_correct'          => $isCorrect,
                'correct_choice_id'   => $correctChoiceId,
                'submitted_choice_id' => $submittedChoiceId,
            ];
        }

        $totalQuestions = $questions->count();
        $percentage     = round(($correctCount / $totalQuestions) * 100);
        $passed         = ($percentage >= 60);
        $attemptNumber  = $existingAttemptCount + 1;

        $attempt = QuizAttempt::create([
            'student_id'     => $studentId,
            'lesson_id'      => $lessonId,
            'attempt_number' => $attemptNumber,
            'score'          => $percentage,
            'passed'         => $passed,
            'submitted_at'   => now(),
        ]);

        foreach ($feedback as $questionId => $fb) {
            QuizAttemptQuestion::create([
                'attempt_id'         => $attempt->id,
                'question_id'        => $questionId,
                'selected_choice_id' => $fb['submitted_choice_id'],
                'is_correct'         => $fb['is_correct'],
            ]);
        }

        if ($passed) {
            StudentProgress::updateOrCreate(
                ['student_id' => $studentId, 'lesson_id' => $lessonId],
                ['completed_at' => now()]
            );
            StudentLessonProgress::updateOrCreate(
                ['user_id' => $studentId, 'lesson_id' => $lessonId],
                ['completed' => true]
            );
            $this->updateCourseProgress($studentId, $course);
        }

        // Compute best score across all attempts including this one
        $bestScore = QuizAttempt::where('student_id', $studentId)
            ->where('lesson_id', $lessonId)
            ->max('score');

        return response()->json([
            'success'        => true,
            'passed'         => $passed,
            'score'          => $correctCount,
            'total'          => $totalQuestions,
            'percentage'     => $percentage,
            'attempt_number' => $attemptNumber,
            'attempts_used'  => $attemptNumber,
            'max_attempts'   => 3,
            'best_score'     => (int) $bestScore,
        ]);
    }

    /**
     * Helper to compute and update overall course progress
     */
    private function updateCourseProgress($studentId, Course $course)
    {
        $lessonIds = Lesson::whereIn('module_id', function ($query) use ($course) {
            $query->select('id')->from('modules')->where('course_id', $course->id);
        })->pluck('id')->toArray();

        $totalCount = count($lessonIds);
        if ($totalCount === 0) return;

        $completedCount = StudentProgress::where('student_id', $studentId)
            ->whereIn('lesson_id', $lessonIds)
            ->whereNotNull('completed_at')
            ->count();

        $progressPercentage = (int) round(($completedCount / $totalCount) * 100);

        StudentCourse::where('user_id', $studentId)
            ->where('course_id', $course->id)
            ->update(['progress' => $progressPercentage]);
    }
}