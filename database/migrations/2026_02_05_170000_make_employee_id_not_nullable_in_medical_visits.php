<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $missing = DB::table('medical_visits')->whereNull('employee_id')->count();
        if ($missing > 0) {
            throw new RuntimeException("Impossible de rendre employee_id obligatoire: {$missing} visite(s) sans employee_id.");
        }

        DB::statement('ALTER TABLE medical_visits MODIFY employee_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        DB::statement('ALTER TABLE medical_visits MODIFY employee_id BIGINT UNSIGNED NULL');
    }
};
