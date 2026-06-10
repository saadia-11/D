<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('camions', function (Blueprint $table) {
            $table->id();
            $table->string('matricule');
            $table->date('date_visite_technique')->nullable();
            $table->string('statut')->default('Disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camions');
    }
};
