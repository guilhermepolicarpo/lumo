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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('zip_code', 9)->nullable();
            $table->char('state', 2)->nullable();
            $table->string('city')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('zip_code');
            $table->index('neighborhood');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
