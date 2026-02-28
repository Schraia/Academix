<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('courses')
            ->where(function ($q) {
                $q->where('code', 'like', 'PPE%')
                    ->orWhere('title', 'like', 'PPE%');
            })
            ->update(['credits' => 2]);
    }

    public function down(): void
    {
        // Optional: revert to 0 if needed. Left no-op so we don't lose data.
    }
};
