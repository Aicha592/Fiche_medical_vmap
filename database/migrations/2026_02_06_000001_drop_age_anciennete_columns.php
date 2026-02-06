<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'age') || Schema::hasColumn('users', 'anciennete')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'age')) {
                    $table->dropColumn('age');
                }
                if (Schema::hasColumn('users', 'anciennete')) {
                    $table->dropColumn('anciennete');
                }
            });
        }

        if (Schema::hasColumn('employees', 'age') || Schema::hasColumn('employees', 'anciennete')) {
            Schema::table('employees', function (Blueprint $table) {
                if (Schema::hasColumn('employees', 'age')) {
                    $table->dropColumn('age');
                }
                if (Schema::hasColumn('employees', 'anciennete')) {
                    $table->dropColumn('anciennete');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('users', 'anciennete')) {
                $table->string('anciennete')->nullable();
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'age')) {
                $table->integer('age')->nullable();
            }
            if (!Schema::hasColumn('employees', 'anciennete')) {
                $table->string('anciennete')->nullable();
            }
        });
    }
};
