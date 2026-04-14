<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('college_sections', function (Blueprint $table) {
            if (!Schema::hasColumn('college_sections', 'student_slots')) {
                $table->unsignedSmallInteger('student_slots')->default(40)->after('time_slot');
            }
        });
    }

    public function down(): void
    {
        Schema::table('college_sections', function (Blueprint $table) {
            if (Schema::hasColumn('college_sections', 'student_slots')) {
                $table->dropColumn('student_slots');
            }
        });
    }
};
