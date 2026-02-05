<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $missing = DB::table('medical_visits')->whereNull('employee_id')->count();
        if ($missing > 0) {
            throw new RuntimeException("Impossible de rendre employee_id obligatoire: {$missing} visite(s) sans employee_id.");
        }

        // MySQL/MariaDB: enforce NOT NULL.
        DB::statement('ALTER TABLE medical_visits MODIFY employee_id BIGINT UNSIGNED NOT NULL');
    }

    public function down(): void
    {
        // MySQL/MariaDB: allow NULL.
        DB::statement('ALTER TABLE medical_visits MODIFY employee_id BIGINT UNSIGNED NULL');
    }
};
