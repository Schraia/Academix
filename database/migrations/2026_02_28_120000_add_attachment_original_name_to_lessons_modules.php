<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons_modules', function (Blueprint $table) {
            $table->string('attachment_original_name')->nullable()->after('attachment_path');
        });
    }

    public function down(): void
    {
        Schema::table('lessons_modules', function (Blueprint $table) {
            $table->dropColumn('attachment_original_name');
        });
    }
};
