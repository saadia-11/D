<?php
// generate.php - Script PHP pur pour créer le .zip de l'architecture jdidda

$zipName = "logitrack_erp_complet_luxury.zip";
$zip = new ZipArchive();

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("Erreur : Impossible de créer le fichier ZIP");
}

$files = [
    // 📂 MODELS
    "app/Models/Client.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Client extends Model {
    protected $fillable = ["nom", "prenom", "email", "telephone", "adresse", "entreprise", "ice"];
    public function dossiers() { return $this->hasMany(DossierTransit::class); }
}',

    "app/Models/Chauffeur.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Chauffeur extends Model {
    protected $fillable = ["nom", "prenom", "telephone", "matricule_camion", "statut_dispo", "permis_categorie"];
    public function dossiers() { return $this->hasMany(DossierTransit::class); }
    public function maintenances() { return $this->hasMany(Maintenance::class); }
    public function frais() { return $this->hasMany(FraisRoute::class); }
}',

    "app/Models/DossierTransit.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DossierTransit extends Model {
    protected $table = "dossiers_transit";
    protected $fillable = ["client_id", "chauffeur_id", "numero_dum", "mode_transport", "provenance_destination", "description_marchandise", "valeur_declarée", "statut"];
    public function client() { return $this->belongsTo(Client::class); }
    public function chauffeur() { return $this->belongsTo(Chauffeur::class); }
    public function facture() { return $this->hasOne(Facture::class, "dossier_id"); }
    public function fraisRoute() { return $this->hasMany(FraisRoute::class, "dossier_id"); }
}',

    "app/Models/FraisRoute.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FraisRoute extends Model {
    protected $table = "frais_route";
    protected $fillable = ["dossier_id", "chauffeur_id", "montant_gasoil", "montant_peage", "autres_frais", "note"];
    public function dossier() { return $this->belongsTo(DossierTransit::class); }
    public function chauffeur() { return $this->belongsTo(Chauffeur::class); }
}',

    "app/Models/Maintenance.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Maintenance extends Model {
    protected $fillable = ["chauffeur_id", "type_panne", "date_reparation", "montant_facture", "statut"];
    public function chauffeur() { return $this->belongsTo(Chauffeur::class); }
}',

    "app/Models/Facture.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Facture extends Model {
    protected $fillable = ["dossier_id", "numero_facture", "date_facture", "montant_prestations", "montant_debours", "total_ttc", "statut_paiement"];
    public function dossier() { return $this->belongsTo(DossierTransit::class); }
}',

    // 📂 MIGRATIONS
    "database/migrations/2026_06_01_000001_create_clients_table.php" => '<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("clients", function (Blueprint $table) {
            $table->id(); $table->string("nom"); $table->string("prenom");
            $table->string("email")->unique(); $table->string("telephone")->nullable();
            $table->string("adresse")->nullable(); $table->string("entreprise")->nullable();
            $table->string("ice")->nullable(); $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("clients"); }
};',

    "database/migrations/2026_06_01_000002_create_chauffeurs_table.php" => '<?php
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
};',

    "database/migrations/2026_06_01_000003_create_dossiers_transit_table.php" => '<?php
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
};',

    // 📂 CONTROLLERS
    "app/Http/Controllers/DossierTransitController.php" => '<?php
namespace App\Http\Controllers;
use App\Models\{DossierTransit, Client, Chauffeur};
use Illuminate\Http\Request;
class DossierTransitController extends Controller {
    public function index() {
        $dossiers = DossierTransit::with(["client", "chauffeur"])->latest()->get();
        $clients = Client::all(); $chauffeurs = Chauffeur::where("statut_dispo", "Disponible")->get();
        return view("dossiers.index", compact("dossiers", "clients", "chauffeurs"));
    }
    public function store(Request $request) { DossierTransit::create($request->all()); return redirect()->route("dossiers.index")->with("success", "Dossier ouvert."); }
}',

    "app/Http/Controllers/FactureController.php" => '<?php
namespace App\Http\Controllers;
use App\Models\{Facture, DossierTransit};
use Illuminate\Http\Request;
class FactureController extends Controller {
    public function index() { $factures = Facture::with("dossier.client")->latest()->get(); $dossiers = DossierTransit::doesntHave("facture")->where("statut", "!=", "Créé")->get(); return view("factures.index", compact("factures", "dossiers")); }
    public function store(Request $request) {
        $total = ($request->montant_prestations * 1.20) + $request->montant_debours;
        Facture::create(["dossier_id" => $request->dossier_id, "numero_facture" => "TR-2026-".rand(100,999), "date_facture" => now(), "montant_prestations" => $request->montant_prestations, "montant_debours" => $request->montant_debours, "total_ttc" => $total]);
        return redirect()->route("factures.index")->with("success", "Facture générée.");
    }
}'
];

foreach ($files as $path => $content) {
    // Écriture du code dans l'archive directement sans modifier tes vrais dossiers locaux
    $zip->addFromString($path, $content);
}

$zip->close();
echo "\n✨ BIEN JOUÉ SAADIA ! Le fichier [ logitrack_erp_complet_luxury.zip ] a été créé avec succès dans ton dossier StageStage ! ✨\n";