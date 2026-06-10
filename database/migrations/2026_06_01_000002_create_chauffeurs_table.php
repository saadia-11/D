<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("chauffeurs", function (Blueprint $table) {
            $table->id(); $table->string("nom"); $table->string("prenom"); $table->string("telephone")->nullable();
            $table->string("matricule_camion")->unique(); $table->string("permis_categorie")->default("EC");
            $table->enum("statut_dispo", ["Disponible", "En Mission", "En Panne"])->default("Disponible");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("chauffeurs"); }
};