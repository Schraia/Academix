<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('name'); // e.g. "Quiz 1", "Final Exam"
            $table->decimal('score', 8, 2)->nullable();
            $table->decimal('max_score', 8, 2)->default(100);
            $table->timestamp('graded_at')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_grades');
    }
};
