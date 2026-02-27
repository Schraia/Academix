<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_grade_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->string('category', 32); // exam, quiz, activity, attendance
            $table->decimal('percentage', 5, 2)->default(0); // e.g. 40.00 for 40%
            $table->timestamps();
            $table->unique(['course_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_grade_weights');
    }
};
