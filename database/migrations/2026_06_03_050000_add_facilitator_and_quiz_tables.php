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
        // 1. Update courses table
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('status')->default('draft'); // draft, pending, approved, returned
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('admin_feedback')->nullable();
        });

        // 2. Update lessons table
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('presentation_path')->nullable();
            $table->string('presentation_size')->nullable();
            $table->integer('quiz_questions_count')->default(5);
        });

        // 3. Create questions table
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->text('question_text');
            $table->string('question_type')->default('multiple_choice');
            $table->timestamps();
        });

        // 4. Create question_options table
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->string('option_text');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['presentation_path', 'presentation_size', 'quiz_questions_count']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status', 'approved_at', 'is_active', 'admin_feedback']);
        });
    }
};
