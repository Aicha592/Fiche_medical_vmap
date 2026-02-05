<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_visits', function (Blueprint $table) {
            $table->string('manutention_frequence')->nullable()->after('contrainte_manutention');
            $table->string('manutention_precision')->nullable()->after('manutention_frequence');
            $table->string('postures_penibilite')->nullable()->after('contrainte_postures');
            $table->string('epi_autres')->nullable()->after('epi_disponibilite');
        });
    }

    public function down(): void
    {
        Schema::table('medical_visits', function (Blueprint $table) {
            $table->dropColumn([
                'manutention_frequence',
                'manutention_precision',
                'postures_penibilite',
                'epi_autres',
            ]);
        });
    }
};
