<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('matricule')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_embauche')->nullable();
            $table->string('emploi_occupe')->nullable();
            $table->string('direction')->nullable();
            $table->string('delegation_r')->nullable();
            $table->string('service')->nullable();
            $table->string('unite_communale')->nullable();
            $table->string('telephone')->nullable();
            $table->date('date_passage')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['matricule']);
            $table->dropColumn([
                'nom',
                'prenom',
                'matricule',
                'sexe',
                'age',
                'direction',
                'poste',
                'anciennete',
                'site',
                'telephone',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->integer('age')->nullable();
            $table->string('direction')->nullable();
            $table->string('poste')->nullable();
            $table->string('anciennete')->nullable();
            $table->enum('site', ['R', 'D', 'C'])->nullable();
            $table->string('telephone')->nullable();
        });

        $employees = DB::table('employees')->get([
            'user_id',
            'matricule',
            'nom',
            'prenom',
            'sexe',
            'age',
            'direction',
            'emploi_occupe',
            'anciennete',
            'site',
            'telephone',
        ]);

        foreach ($employees as $employee) {
            DB::table('users')
                ->where('id', $employee->user_id)
                ->update([
                    'matricule' => $employee->matricule,
                    'nom' => $employee->nom,
                    'prenom' => $employee->prenom,
                    'sexe' => $employee->sexe,
                    'age' => $employee->age,
                    'direction' => $employee->direction,
                    'poste' => $employee->emploi_occupe,
                    'anciennete' => $employee->anciennete,
                    'site' => $employee->site,
                    'telephone' => $employee->telephone,
                ]);
        }

        Schema::dropIfExists('employees');
    }
};
