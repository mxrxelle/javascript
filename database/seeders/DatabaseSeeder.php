<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\StudentCourse;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@certly.com'],
            [
                'name' => 'Certly Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'birthday' => '1990-01-01',
                'affiliation' => 'Certly Org',
                'contact_number' => '09171234567',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Facilitator (Teacher)
        $teacher = User::updateOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'name' => 'Jamesadrew291',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'birthday' => '1985-05-15',
                'affiliation' => 'LMS Faculty',
                'contact_number' => '09177654321',
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Students (matching registered students list in screenshot)
        $student1 = User::updateOrCreate(
            ['email' => 'bautistamarielle1226@gmail.com'],
            [
                'name' => 'Marielle Bautista',
                'password' => Hash::make('password'),
                'role' => 'student',
                'birthday' => '2001-05-07',
                'affiliation' => 'NU Lipa',
                'contact_number' => '09181112222',
                'created_at' => Carbon::parse('2026-05-07 10:00:00'),
                'email_verified_at' => now(),
            ]
        );

        $student2 = User::updateOrCreate(
            ['email' => 'javaricexml@gmail.com'],
            [
                'name' => 'HI World',
                'password' => Hash::make('password'),
                'role' => 'student',
                'birthday' => '2002-06-01',
                'affiliation' => 'NU',
                'contact_number' => '09183334444',
                'created_at' => Carbon::parse('2026-06-01 11:30:00'),
                'email_verified_at' => now(),
            ]
        );

        $student3 = User::updateOrCreate(
            ['email' => 'hehe@g'],
            [
                'name' => 'hehe',
                'password' => Hash::make('password'),
                'role' => 'student',
                'birthday' => '2003-06-03',
                'affiliation' => 'Student',
                'contact_number' => '09185556666',
                'created_at' => Carbon::parse('2026-06-03 09:15:00'),
                'email_verified_at' => now(),
            ]
        );

        // 4. Create Approved Courses
        // Course 1: Advanced Cybersecurity
        $course1 = Course::updateOrCreate(
            ['title' => 'Advanced Cybersecurity'],
            [
                'description' => 'Master network security, cryptography, vulnerability analysis, and incident response.',
                'category' => 'Tech',
                'user_id' => $teacher->id,
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-04-15 12:00:00'),
                'is_active' => true,
            ]
        );

        // Course 2: Cloud Computing Fundamentals
        $course2 = Course::updateOrCreate(
            ['title' => 'Cloud Computing Fundamentals'],
            [
                'description' => 'Introduction to virtualization, cloud architectures, AWS, Azure, and Google Cloud services.',
                'category' => 'Tech',
                'user_id' => $teacher->id,
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-03-22 10:00:00'),
                'is_active' => true,
            ]
        );

        // Course 3: Data Analytics with Python
        $course3 = Course::updateOrCreate(
            ['title' => 'Data Analytics with Python'],
            [
                'description' => 'Learn data manipulation, exploratory data analysis, and visualization using Pandas and Seaborn.',
                'category' => 'Data Science',
                'user_id' => $teacher->id,
                'status' => 'approved',
                'approved_at' => Carbon::parse('2026-02-10 15:30:00'),
                'is_active' => false,
            ]
        );

        // Seed some student enrollments for approved courses
        // To match the screenshot enrollments (234, 187, 145) but keeping it DB seed-friendly,
        // we'll enroll our seeded students and we can also support the UI showing custom numbers
        // if no real enrollments match, but let's seed them so the relations are active!
        StudentCourse::updateOrCreate(
            ['user_id' => $student1->id, 'course_id' => $course1->id],
            ['progress' => 45]
        );
        StudentCourse::updateOrCreate(
            ['user_id' => $student2->id, 'course_id' => $course1->id],
            ['progress' => 80]
        );
        StudentCourse::updateOrCreate(
            ['user_id' => $student3->id, 'course_id' => $course2->id],
            ['progress' => 10]
        );

        // 5. Create Submissions
        // Course 4: Machine Learning Basics (Pending)
        Course::updateOrCreate(
            ['title' => 'Machine Learning Basics'],
            [
                'description' => 'Introduction to supervised and unsupervised learning algorithms, regression, and classification.',
                'category' => 'AI',
                'user_id' => $teacher->id,
                'status' => 'pending',
                'created_at' => Carbon::parse('2026-05-10 09:00:00'),
                'is_active' => true,
            ]
        );

        // Course 5: Advanced DevOps Infrastructure (Returned)
        Course::updateOrCreate(
            ['title' => 'Advanced DevOps Infrastructure'],
            [
                'description' => 'Understand CI/CD pipelines, Docker, Kubernetes, and Infrastructure as Code.',
                'category' => 'Tech',
                'user_id' => $teacher->id,
                'status' => 'returned',
                'created_at' => Carbon::parse('2026-05-05 14:00:00'),
                'updated_at' => Carbon::parse('2026-05-05 14:00:00'),
                'is_active' => true,
                'admin_feedback' => 'Please expand the quiz pool in Module 2 to include more foundational network architecture questions.',
            ]
        );

        // 6. Create Draft Course: Introduction to AI Ethics
        $draftCourse = Course::updateOrCreate(
            ['title' => 'Introduction to AI Ethics'],
            [
                'description' => 'Overview of ethical concerns, alignment, bias, and fairness in machine learning algorithms.',
                'category' => 'Tech',
                'user_id' => $teacher->id,
                'status' => 'draft',
                'created_at' => Carbon::parse('2026-04-28 10:00:00'),
                'updated_at' => Carbon::parse('2026-04-28 10:00:00'),
                'is_active' => true,
            ]
        );

        // Create Module for Draft Course
        $module = Module::updateOrCreate(
            ['course_id' => $draftCourse->id, 'title' => 'Control Flow & Loops'],
            [
                'sort_order' => 4,
            ]
        );

        // Create Subtopic 1
        $lesson1 = Lesson::updateOrCreate(
            ['module_id' => $module->id, 'title' => 'Loops: while and do...while', 'type' => 'presentation'],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=loop_tutorial',
                'presentation_path' => 'Module_4_Loops_DeepDive.pptx',
                'presentation_size' => '4.2 MB',
                'sort_order' => 1,
            ]
        );

        // Create Subtopic 2
        $lesson2 = Lesson::updateOrCreate(
            ['module_id' => $module->id, 'title' => 'Loops: the for loop', 'type' => 'presentation'],
            [
                'youtube_url' => 'https://www.youtube.com/watch?v=for_loop_tutorial',
                'presentation_path' => 'Module_4_ForLoops.pptx',
                'presentation_size' => '2.8 MB',
                'sort_order' => 2,
            ]
        );

        // Create Module Quiz Lesson
        $quizLesson = Lesson::updateOrCreate(
            ['module_id' => $module->id, 'title' => 'Module 4 Assessment: Control Flow', 'type' => 'quiz'],
            [
                'content' => 'Test your understanding of control flow and loops in programming.',
                'quiz_questions_count' => 5,
                'sort_order' => 3,
            ]
        );

        // Create Quiz Questions
        // Question 1
        $question1 = Question::updateOrCreate(
            ['lesson_id' => $quizLesson->id, 'question_text' => 'Which loop structure checks the condition before executing the loop body?'],
            ['question_type' => 'multiple_choice']
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $question1->id, 'option_text' => 'do...while loop'],
            ['is_correct' => false]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question1->id, 'option_text' => 'while loop'],
            ['is_correct' => true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question1->id, 'option_text' => 'for loop'],
            ['is_correct' => false]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question1->id, 'option_text' => 'Both A and B'],
            ['is_correct' => false]
        );

        // Question 2
        $question2 = Question::updateOrCreate(
            ['lesson_id' => $quizLesson->id, 'question_text' => 'What is the output of a loop that never terminates?'],
            ['question_type' => 'multiple_choice']
        );

        QuestionOption::updateOrCreate(
            ['question_id' => $question2->id, 'option_text' => 'Syntax Error'],
            ['is_correct' => false]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question2->id, 'option_text' => 'Runtime Exception'],
            ['is_correct' => false]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question2->id, 'option_text' => 'Infinite Loop'],
            ['is_correct' => true]
        );
        QuestionOption::updateOrCreate(
            ['question_id' => $question2->id, 'option_text' => 'None of the above'],
            ['is_correct' => false]
        );
    }
}
