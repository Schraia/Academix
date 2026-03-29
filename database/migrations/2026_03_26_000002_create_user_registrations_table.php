<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->unique();

            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 20)->nullable();

            $table->unsignedTinyInteger('age');
            $table->string('nationality', 80);
            $table->string('gender', 20);

            $table->string('contact_number', 30);
            $table->string('address_line', 255);
            $table->string('city', 100);
            $table->string('province', 100);
            $table->string('zip_code', 20)->nullable();

            $table->string('guardian_name', 150)->nullable();
            $table->string('guardian_contact_number', 30)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_registrations');
    }
};

