<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_visit_qhses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            $table->json('contrainte_manutention')->nullable();
            $table->string('manutention_frequence')->nullable();
            $table->string('manutention_precision')->nullable();
            $table->json('contrainte_postures')->nullable();
            $table->string('postures_penibilite')->nullable();

            $table->json('nuisances_physiques')->nullable();
            $table->json('nuisances_chimiques')->nullable();

            $table->json('risques_mecaniques')->nullable();
            $table->json('organisation_travail')->nullable();

            $table->json('epi_disponibilite')->nullable();
            $table->string('epi_utilisation')->nullable();
            $table->json('epi_difficultes')->nullable();
            $table->string('epi_autres')->nullable();

            $table->json('formation_sst')->nullable();
            $table->string('appreciation_poste')->nullable();
            $table->text('observations_qhse')->nullable();

            $table->string('synthese_risque')->nullable();
            $table->json('synthese_facteurs')->nullable();
            $table->json('synthese_actions')->nullable();

            $table->timestamps();
            $table->unique('employee_id');
        });

        DB::table('medical_visits')
            ->chunkById(200, function ($visits) {
                $rows = [];
                foreach ($visits as $visit) {
                    if (!$visit->employee_id) {
                        continue;
                    }
                    $rows[] = [
                        'employee_id' => $visit->employee_id,
                        'contrainte_manutention' => $visit->contrainte_manutention,
                        'manutention_frequence' => $visit->manutention_frequence,
                        'manutention_precision' => $visit->manutention_precision,
                        'contrainte_postures' => $visit->contrainte_postures,
                        'postures_penibilite' => $visit->postures_penibilite,
                        'nuisances_physiques' => $visit->nuisances_physiques,
                        'nuisances_chimiques' => $visit->nuisances_chimiques,
                        'risques_mecaniques' => $visit->risques_mecaniques,
                        'organisation_travail' => $visit->organisation_travail,
                        'epi_disponibilite' => $visit->epi_disponibilite,
                        'epi_utilisation' => $visit->epi_utilisation,
                        'epi_difficultes' => $visit->epi_difficultes,
                        'epi_autres' => $visit->epi_autres,
                        'formation_sst' => $visit->formation_sst,
                        'appreciation_poste' => $visit->appreciation_poste,
                        'observations_qhse' => $visit->observations_qhse,
                        'synthese_risque' => $visit->synthese_risque,
                        'synthese_facteurs' => $visit->synthese_facteurs,
                        'synthese_actions' => $visit->synthese_actions,
                        'created_at' => $visit->created_at,
                        'updated_at' => $visit->updated_at,
                    ];
                }

                if ($rows) {
                    DB::table('medical_visit_qhses')->upsert($rows, ['employee_id']);
                }
            });

        Schema::table('medical_visits', function (Blueprint $table) {
            $table->dropColumn([
                'contrainte_manutention',
                'manutention_frequence',
                'manutention_precision',
                'contrainte_postures',
                'postures_penibilite',
                'nuisances_physiques',
                'nuisances_chimiques',
                'risques_mecaniques',
                'organisation_travail',
                'epi_disponibilite',
                'epi_utilisation',
                'epi_difficultes',
                'epi_autres',
                'formation_sst',
                'appreciation_poste',
                'observations_qhse',
                'synthese_risque',
                'synthese_facteurs',
                'synthese_actions',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('medical_visits', function (Blueprint $table) {
            $table->json('contrainte_manutention')->nullable();
            $table->string('manutention_frequence')->nullable();
            $table->string('manutention_precision')->nullable();
            $table->json('contrainte_postures')->nullable();
            $table->string('postures_penibilite')->nullable();

            $table->json('nuisances_physiques')->nullable();
            $table->json('nuisances_chimiques')->nullable();

            $table->json('risques_mecaniques')->nullable();
            $table->json('organisation_travail')->nullable();

            $table->json('epi_disponibilite')->nullable();
            $table->string('epi_utilisation')->nullable();
            $table->json('epi_difficultes')->nullable();
            $table->string('epi_autres')->nullable();

            $table->json('formation_sst')->nullable();
            $table->string('appreciation_poste')->nullable();
            $table->text('observations_qhse')->nullable();

            $table->string('synthese_risque')->nullable();
            $table->json('synthese_facteurs')->nullable();
            $table->json('synthese_actions')->nullable();
        });

        DB::table('medical_visit_qhses')
            ->chunkById(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('medical_visits')
                        ->where('employee_id', $row->employee_id)
                        ->update([
                            'contrainte_manutention' => $row->contrainte_manutention,
                            'manutention_frequence' => $row->manutention_frequence,
                            'manutention_precision' => $row->manutention_precision,
                            'contrainte_postures' => $row->contrainte_postures,
                            'postures_penibilite' => $row->postures_penibilite,
                            'nuisances_physiques' => $row->nuisances_physiques,
                            'nuisances_chimiques' => $row->nuisances_chimiques,
                            'risques_mecaniques' => $row->risques_mecaniques,
                            'organisation_travail' => $row->organisation_travail,
                            'epi_disponibilite' => $row->epi_disponibilite,
                            'epi_utilisation' => $row->epi_utilisation,
                            'epi_difficultes' => $row->epi_difficultes,
                            'epi_autres' => $row->epi_autres,
                            'formation_sst' => $row->formation_sst,
                            'appreciation_poste' => $row->appreciation_poste,
                            'observations_qhse' => $row->observations_qhse,
                            'synthese_risque' => $row->synthese_risque,
                            'synthese_facteurs' => $row->synthese_facteurs,
                            'synthese_actions' => $row->synthese_actions,
                        ]);
                }
            });

        Schema::dropIfExists('medical_visit_qhses');
    }
};
