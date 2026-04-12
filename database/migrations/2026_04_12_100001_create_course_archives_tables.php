<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('label');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['course_id', 'created_at']);
        });

        Schema::create('archived_lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_archive_id')->constrained('course_archives')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('content')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unsignedBigInteger('source_lesson_id')->nullable();
            $table->timestamps();
        });

        Schema::create('instructor_blocked_course_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_archive_id')->constrained('course_archives')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'course_archive_id'], 'ibca_user_archive_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_blocked_course_archives');
        Schema::dropIfExists('archived_lessons');
        Schema::dropIfExists('course_archives');
    }
};
