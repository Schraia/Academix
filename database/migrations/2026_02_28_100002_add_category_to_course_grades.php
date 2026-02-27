<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_grades', function (Blueprint $table) {
            $table->string('category', 32)->nullable()->after('name'); // exam, quiz, activity
        });
    }

    public function down(): void
    {
        Schema::table('course_grades', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
