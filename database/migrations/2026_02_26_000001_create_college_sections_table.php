<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('college_sections')) {
            Schema::create('college_sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('college_course_id')->constrained('college_courses')->onDelete('cascade');
                $table->unsignedTinyInteger('year');
                $table->unsignedTinyInteger('semester');
                $table->string('section_code', 20);
                $table->string('time_slot')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['college_course_id', 'year', 'semester', 'section_code'], 'college_sections_ccid_y_s_sc_unique');
            });
        } else {
            Schema::table('college_sections', function (Blueprint $table) {
                $table->unique(['college_course_id', 'year', 'semester', 'section_code'], 'college_sections_ccid_y_s_sc_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('college_sections');
    }
};
