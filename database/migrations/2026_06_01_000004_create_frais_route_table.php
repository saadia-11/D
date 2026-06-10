<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("frais_route", function (Blueprint $table) {
            $table->id();
            $table->foreignId("dossier_id")->constrained("dossiers_transit")->onDelete("cascade");
            $table->foreignId("chauffeur_id")->constrained("chauffeurs")->onDelete("cascade");
            $table->double("montant_gasoil")->default(0); $table->double("montant_peage")->default(0);
            $table->double("autres_frais")->default(0); $table->string("note")->nullable(); $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("frais_route"); }
};