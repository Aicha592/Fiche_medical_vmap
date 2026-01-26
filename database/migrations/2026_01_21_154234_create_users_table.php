<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();  // BIGINT UNSIGNED AUTO_INCREMENT
            $table->string('nom');
            $table->string('prenom');
            $table->string('matricule')->unique();
            $table->enum('sexe', ['M', 'F']);
            $table->integer('age')->nullable();
            $table->string('direction');
            $table->string('poste');
            $table->string('anciennete')->nullable();
            $table->enum('site', ['R', 'D', 'C']);
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->boolean('is_doctor')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['email', 'password', 'is_doctor']);
    });
}

};
