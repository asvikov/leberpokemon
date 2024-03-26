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
        Schema::create('pokemon_shape', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id');
            $table->foreignId('shape_id');
            $table->timestamps();
            //$table->unique(['company_id', 'worker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemon_shape');
    }
};
