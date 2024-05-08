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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('treatment_type_id')->constrained('types_of_treatments');
            $table->foreignId('mentor_id')->constrained();
            $table->string('treatment_mode', 50);
            $table->text('notes')->nullable();
            $table->string('infiltracao')->nullable();
            $table->dateTime('infiltracao_remove_date')->nullable();
            $table->json('healing_touches')->nullable();
            $table->string('return_mode')->nullable();
            $table->date('return_date')->nullable();
            $table->string('magnetized_water_frequency', 50)->nullable();
            $table->timestamps();

            // Indexes
            $table->index('patient_id');
            $table->index('treatment_type_id');
            $table->index('mentor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
