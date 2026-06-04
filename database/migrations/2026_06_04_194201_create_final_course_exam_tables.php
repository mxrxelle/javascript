<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('final_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->integer('passing_score')->default(70);
            $table->timestamps();
        });

        Schema::create('final_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('final_exam_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('final_exam_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('final_exam_questions')->onDelete('cascade');
            $table->text('choice_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('final_exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->float('score', 8, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->integer('correct_count')->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            // Only 1 attempt allowed per student per course
            $table->unique(['student_id', 'course_id']);
        });

        Schema::create('final_exam_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('final_exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('final_exam_questions')->onDelete('cascade');
            $table->foreignId('selected_choice_id')->nullable()->constrained('final_exam_choices')->nullOnDelete();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('certificate_uid')->unique();
            $table->string('file_path');
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();
            
            // Only 1 certificate allowed per student per course
            $table->unique(['student_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('final_exam_attempt_answers');
        Schema::dropIfExists('final_exam_attempts');
        Schema::dropIfExists('final_exam_choices');
        Schema::dropIfExists('final_exam_questions');
        Schema::dropIfExists('final_exams');
    }
};
