<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_registrations', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('age');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('institutional_email')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('institutional_email');
        });

        Schema::table('user_registrations', function (Blueprint $table) {
            $table->dropColumn('birthdate');
        });
    }
};
