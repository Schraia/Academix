<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('section_code', 50)->nullable();
            $table->date('date');
            $table->string('status', 20)->default('present'); // present, late, absent, none
            $table->timestamps();
            $table->unique(['course_id', 'user_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_attendance');
    }
};
