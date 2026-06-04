<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // On supprime l'ancienne table si elle existe pour la rebâtir proprement
        //Schema::dropIfExists('medical_visit_qhses');

        Schema::create('qhse_evaluations', function (Blueprint $table) {
            $table->id();

            // LIAISON AVEC L'EMPLOYÉ (Clé étrangère stricte)
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            // 1. IDENTIFICATION COMPLÉMENTAIRE (Les autres infos sont dans la table 'employees')
            $table->json('poste_occupe')->nullable(); // Liste des postes occupés par l'agent (ex: ['Opérateur machine', 'Superviseur'])
            $table->enum('type_activite_dominante', ['Terrain', 'Bureau', 'Mixte']);
            $table->json('horaire_travail'); // Choix multiples : Jour, Après-midi, Nuit

            // 2. CONTRAINTES PHYSIQUES ET ERGONOMIQUES
            $table->json('manutention_efforts')->nullable(); // Choix multiples
            $table->enum('frequence_manutention', ['Rare', 'Occasionnelle', 'Fréquente', 'Permanente']);
            $table->json('gestes_postures')->nullable(); // Choix multiples
            $table->unsignedTinyInteger('niveau_penibilite'); // Note de 1 à 5
            $table->json('outils_travail')->nullable(); // Choix multiples
            $table->string('outils_travail_autre')->nullable();

            // 3. EXPOSITION AUX NUISANCES PROFESSIONNELLES
            $table->json('nuisances_physiques')->nullable();
            $table->json('nuisances_chimiques_biologiques')->nullable();

            // 4. RISQUES ACCIDENTELS
            $table->json('risques_accidentels')->nullable();
            $table->boolean('temoin_accident'); // 1 = Oui, 0 = Non

            // 5. ORGANISATION DU TRAVAIL
            $table->json('organisation_travail')->nullable();

            // 6. ÉQUIPEMENTS DE PROTECTION INDIVIDUELLE (EPI)
            $table->json('epi_fournis')->nullable();
            $table->string('epi_fournis_autres')->nullable();
            $table->json('epi_utilises_quotidien')->nullable();
            $table->string('epi_utilises_autres')->nullable();
            $table->json('epi_difficultes')->nullable();

            // 7. FORMATION ET SENSIBILISATION
            $table->json('formations_recues')->nullable();
            $table->date('date_derniere_formation')->nullable();

            // 8. APPRÉCIATION GLOBALE DE L'AGENT
            $table->enum('niveau_risque_agent', ['Faible', 'Modéré', 'Élevé', 'Très élevé']);
            $table->boolean('ameliorations_necessaires'); // 1 = Oui, 0 = Non
            $table->text('suggestions_amelioration')->nullable();

            // 9. SYNTHÈSE QHSE (Remplie par le service QHSE plus tard -> Nullable)
            $table->enum('synthese_niveau_risque', ['Faible', 'Modéré', 'Élevé', 'Critique'])->nullable();
            $table->json('synthese_facteurs_dominants')->nullable();
            $table->json('synthese_actions_prioritaires')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qhse_evaluations');
    }
};
