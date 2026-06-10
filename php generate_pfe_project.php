<?php
// Script pour générer l'architecture complète du projet Stage
$zipName = "LogiTrack_ERP_Complet.zip";
$zip = new ZipArchive();

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("❌ Erreur : Impossible de créer le ZIP.");
}

$files = [
    // 1. Model Camion avec Alertes
    "app/Models/Camion.php" => '<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Camion extends Model {
    protected $fillable = ["matricule", "date_visite_technique", "statut"];
    public function getAlerteAttribute() {
        $diff = Carbon::now()->diffInDays(Carbon::parse($this->date_visite_technique), false);
        if ($diff <= 7 && $diff >= 0) return ["msg" => "Visite dans ".$diff." jours", "type" => "warning"];
        if ($diff < 0) return ["msg" => "Visite expirée !", "type" => "danger"];
        return null;
    }
}',

    // 2. View Dashboard avec Alertes (Luxury)
    "resources/views/dashboard.blade.php" => '
@extends("layouts.app")
@section("content")
<div class="row">
    @foreach($camions as $camion)
        @if($alert = $camion->alerte)
            <div class="alert alert-{{ $alert["type"] }} shadow-sm">
                <strong>{{ $camion->matricule }}:</strong> {{ $alert["msg"] }}
            </div>
        @endif
    @endforeach
</div>
@endsection',

    // 3. Exemple de Facture pour PDF (Blade)
    "resources/views/factures/pdf.blade.php" => '
<html>
<body style="font-family: sans-serif;">
    <h1 style="color: #6b21a8;">Facture #{{ $facture->id }}</h1>
    <p>Client: {{ $facture->client->nom }}</p>
    <hr>
    <p>Total HT: {{ $facture->montant_ht }} DH</p>
    <p>Total TTC: {{ ($facture->montant_ht * 1.2) + $facture->debours }} DH</p>
</body>
</html>'
];

foreach ($files as $path => $content) {
    $zip->addFromString($path, $content);
}

$zip->close();
echo "✅ Fichier [$zipName] généré avec succès ! Il suffit de faire 'Extraire tout'.\n";