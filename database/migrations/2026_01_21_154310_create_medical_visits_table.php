<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->json('antecedents')->nullable();
            $table->text('antecedents_precisions')->nullable();

            $table->float('taille')->nullable();
            $table->float('poids')->nullable();
            $table->float('imc')->nullable();
            $table->string('tension')->nullable();

            $table->string('stress')->nullable();
            $table->string('sommeil')->nullable();
            $table->string('charge_travail')->nullable();
            $table->string('soutien')->nullable();

            $table->string('avis')->nullable();
            $table->text('observations')->nullable();

            // ===== QHSE / SST =====
            $table->json('contrainte_manutention')->nullable();
            $table->json('contrainte_postures')->nullable();

            $table->json('nuisances_physiques')->nullable();
            $table->json('nuisances_chimiques')->nullable();

            $table->json('risques_mecaniques')->nullable();
            $table->json('organisation_travail')->nullable();

            $table->json('epi_disponibilite')->nullable();
            $table->string('epi_utilisation')->nullable();
            $table->json('epi_difficultes')->nullable();

            $table->json('formation_sst')->nullable();
            $table->string('appreciation_poste')->nullable();
            $table->text('observations_qhse')->nullable();

            $table->string('synthese_risque')->nullable();
            $table->json('synthese_facteurs')->nullable();
            $table->json('synthese_actions')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_visits');
    }
};
