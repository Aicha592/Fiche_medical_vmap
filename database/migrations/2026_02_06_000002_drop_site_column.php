<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'site')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('site');
            });
        }

        if (Schema::hasColumn('employees', 'site')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('site');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'site')) {
                $table->enum('site', ['R', 'D', 'C'])->nullable();
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'site')) {
                $table->enum('site', ['R', 'D', 'C'])->nullable();
            }
        });
    }
};
