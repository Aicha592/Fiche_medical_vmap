<?php

use App\Models\Employee;
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
        Schema::create('blood_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class)->constrained()->onDelete('cascade');
            $table->decimal('uree', 8, 2)->nullable();
            $table->decimal('creat', 8, 2)->nullable();
            $table->decimal('asat', 8, 2)->nullable();
            $table->decimal('alat', 8, 2)->nullable();
            $table->string('aghbs')->nullable();
            $table->decimal('chol', 8, 2)->nullable();
            $table->decimal('tg', 8, 2)->nullable();
            $table->decimal('gaj', 8, 2)->nullable();
            $table->decimal('hb', 8, 2)->nullable();
            $table->decimal('hct', 8, 2)->nullable();
            $table->decimal('gb', 8, 2)->nullable();
            $table->decimal('plt', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_tests');
    }
};
