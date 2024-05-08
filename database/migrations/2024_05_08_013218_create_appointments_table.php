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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('treatment_type_id')->constrained('types_of_treatments');
            $table->foreignId('treatment_id')->nullable()->constrained();
            $table->date('date');
            $table->string('treatment_mode', 50);
            $table->string('status', 50);
            $table->text('notes')->nullable();
            $table->string('who_requested_it')->nullable();
            $table->string('who_requested_it_phone')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('patient_id');
            $table->index('treatment_type_id');
            $table->index('treatment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
