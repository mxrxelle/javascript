<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('filename');   // Original filename
            $table->string('path');       // File path in storage
            $table->string('type');       // pdf, pptx, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_files');
    }
};