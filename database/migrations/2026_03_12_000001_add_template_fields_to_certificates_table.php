<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedTinyInteger('template_id')->nullable()->after('certificate_number');
            $table->string('signer_name')->nullable()->after('template_id');
            $table->string('subtitle')->nullable()->after('signer_name');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['template_id', 'signer_name', 'subtitle']);
        });
    }
};
