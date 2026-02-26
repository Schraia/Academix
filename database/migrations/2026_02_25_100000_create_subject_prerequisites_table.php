<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Defines prerequisites for subjects like PPE/MLC (e.g. PPE 1st year 2nd sem requires 1st year 1st sem).
     */
    public function up(): void
    {
        Schema::create('subject_prerequisites', function (Blueprint $table) {
            $table->id();
            $table->string('subject_type', 20); // PPE, MLC
            $table->unsignedTinyInteger('year');   // 1-4
            $table->unsignedTinyInteger('semester'); // 1 or 2
            $table->unsignedTinyInteger('req_year');
            $table->unsignedTinyInteger('req_semester');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_prerequisites');
    }
};
