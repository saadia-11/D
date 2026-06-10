<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("maintenances", function (Blueprint $table) {
            $table->id(); $table->foreignId("chauffeur_id")->constrained("chauffeurs")->onDelete("cascade");
            $table->string("type_panne"); $table->date("date_reparation");
            $table->double("montant_facture")->default(0); $table->enum("statut", ["En Cours", "Réparé"])->default("En Cours");
            $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("maintenances"); }
};