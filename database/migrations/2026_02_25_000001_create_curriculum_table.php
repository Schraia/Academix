<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('college_course_id')->constrained('college_courses')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->unsignedTinyInteger('year'); // 1-4
            $table->unsignedTinyInteger('semester'); // 1 or 2
            $table->string('prerequisites')->nullable(); // e.g. "P-CCS 1101" or "E-CCS 4303"
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['college_course_id', 'course_id', 'year', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum');
    }
};
