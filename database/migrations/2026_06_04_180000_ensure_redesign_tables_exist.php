<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure columns exist on lessons table
        Schema::table('lessons', function (Blueprint $table) {
            // Change type to string to support 'pdf' without enum issues
            $table->string('type')->change();
            
            if (!Schema::hasColumn('lessons', 'order')) {
                $table->integer('order')->nullable();
            }
            if (!Schema::hasColumn('lessons', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('lessons', 'video_url')) {
                $table->string('video_url')->nullable();
            }
        });

        // Sync initial data from sort_order/presentation_path/youtube_url
        DB::table('lessons')->update([
            'order' => DB::raw('sort_order'),
            'file_path' => DB::raw('presentation_path'),
            'video_url' => DB::raw('youtube_url'),
        ]);

        // 2. Ensure columns exist on lesson_files table
        Schema::table('lesson_files', function (Blueprint $table) {
            if (!Schema::hasColumn('lesson_files', 'file_type')) {
                $table->string('file_type')->nullable();
            }
            if (!Schema::hasColumn('lesson_files', 'file_name')) {
                $table->string('file_name')->nullable();
            }
            if (!Schema::hasColumn('lesson_files', 'file_path')) {
                $table->string('file_path')->nullable();
            }
        });

        // Sync initial data from filename/path/type
        DB::table('lesson_files')->update([
            'file_type' => DB::raw('type'),
            'file_name' => DB::raw('filename'),
            'file_path' => DB::raw('path'),
        ]);

        // 3. Create quiz_questions table if not exists
        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
                $table->text('question');
                $table->string('type')->default('multiple_choice');
                $table->timestamps();
            });
        }

        // 4. Create quiz_choices table if not exists
        if (!Schema::hasTable('quiz_choices')) {
            Schema::create('quiz_choices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('question_id')->constrained('quiz_questions')->onDelete('cascade');
                $table->text('choice_text');
                $table->boolean('is_correct')->default(false);
                $table->timestamps();
            });
        }

        // Migrate existing questions/options data to quiz_questions and quiz_choices
        $oldQuestions = DB::table('questions')->get();
        foreach ($oldQuestions as $oldQ) {
            $newQId = DB::table('quiz_questions')->insertGetId([
                'id' => $oldQ->id,
                'lesson_id' => $oldQ->lesson_id,
                'question' => $oldQ->question_text,
                'type' => $oldQ->question_type,
                'created_at' => $oldQ->created_at,
                'updated_at' => $oldQ->updated_at,
            ]);

            $oldOptions = DB::table('question_options')->where('question_id', $oldQ->id)->get();
            foreach ($oldOptions as $oldO) {
                DB::table('quiz_choices')->insert([
                    'id' => $oldO->id,
                    'question_id' => $newQId,
                    'choice_text' => $oldO->option_text,
                    'is_correct' => $oldO->is_correct,
                    'created_at' => $oldO->created_at,
                    'updated_at' => $oldO->updated_at,
                ]);
            }
        }

        // 5. Create student_progress table if not exists
        if (!Schema::hasTable('student_progress')) {
            Schema::create('student_progress', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();

                $table->unique(['student_id', 'lesson_id']);
            });
        }

        // Migrate existing student_lesson_progress data
        $oldProgress = DB::table('student_lesson_progress')->get();
        foreach ($oldProgress as $p) {
            DB::table('student_progress')->insertOrIgnore([
                'student_id' => $p->user_id,
                'lesson_id' => $p->lesson_id,
                'completed_at' => $p->completed ? $p->updated_at : null,
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
        Schema::dropIfExists('quiz_choices');
        Schema::dropIfExists('quiz_questions');
        
        Schema::table('lesson_files', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'file_name', 'file_path']);
        });

        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['order', 'file_path', 'video_url']);
        });
    }
};
