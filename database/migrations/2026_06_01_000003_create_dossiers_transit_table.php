<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("dossiers_transit", function (Blueprint $table) {
            $table->id();
            $table->foreignId("client_id")->constrained()->onDelete("cascade");
            $table->foreignId("chauffeur_id")->nullable()->constrained("chauffeurs")->onDelete("set null");
            $table->string("numero_dum")->unique()->nullable();
            $table->enum("mode_transport", ["Maritime", "Aérien", "Routier"])->default("Maritime");
            $table->string("provenance_destination"); $table->text("description_marchandise")->nullable();
            $table->double("valeur_declarée")->default(0);
            $table->enum("statut", ["Créé", "En Douane", "Dédouané", "En Cours de Livraison", "Livré"])->default("Créé");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("dossiers_transit"); }
};