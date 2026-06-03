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
        Schema::create('lessons', function (Blueprint $table) {
    $table->id();
    $table->foreignId('module_id')->constrained('modules')->onDelete('cascade');
    $table->string('title');
    $table->enum('type', ['video', 'reading', 'quiz', 'presentation']);
    $table->longText('content')->nullable();
    $table->string('youtube_url')->nullable();
    $table->integer('sort_order')->default(1);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
