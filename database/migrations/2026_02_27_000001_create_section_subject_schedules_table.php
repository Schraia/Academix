<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('section_subject_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_section_id')->constrained('college_sections')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('time_slot');
            $table->timestamps();

            $table->unique(['college_section_id', 'course_id'], 'section_subject_sched_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_subject_schedules');
    }
};
