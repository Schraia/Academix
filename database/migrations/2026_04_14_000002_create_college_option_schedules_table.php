<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('college_option_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_course_id')->constrained('college_courses')->onDelete('cascade');
            $table->unsignedTinyInteger('year');
            $table->unsignedTinyInteger('semester');
            $table->string('option_type', 10); // PE or MLC
            $table->string('option_code', 20); // PE-1, MLC-1
            $table->string('option_label', 120); // Badminton, Literacy (STC)
            $table->string('course_code', 30)->nullable(); // PPE 1101, MLC 1001
            $table->string('time_slot')->nullable();
            $table->string('days', 20)->nullable();
            $table->unsignedSmallInteger('student_slots')->default(40);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(
                ['college_course_id', 'year', 'semester', 'option_type', 'option_code'],
                'college_option_sched_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('college_option_schedules');
    }
};
