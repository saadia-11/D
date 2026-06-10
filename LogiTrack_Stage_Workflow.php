<?php
$zip = new ZipArchive();
$zipName = "LogiTrack_Workflow_Complet.zip";

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("❌ Error : Impossible de créer le ZIP.");
}

$files = [
    // 1. Model Dossier avec Historique & Rentabilité (Etape 2)
    "app/Models/DossierTransit.php" => '<?php namespace App\Models; use Illuminate\Database\Eloquent\Model; class DossierTransit extends Model { public function fraisRoute() { return $this->hasMany(FraisRoute::class, "dossier_id"); } public function maintenances() { return $this->hasMany(Maintenance::class, "dossier_id"); } public function getRentabiliteAttribute() { return $this->valeur_declarée - ($this->fraisRoute->sum("montant_gasoil") + $this->maintenances->sum("montant_facture")); } }',

    // 2. View Historique Dossier (Luxury Style)
    "resources/views/dossiers/show.blade.php" => '@extends("layouts.app") @section("content") <div class="card p-4"><h3>Dossier #{{$dossier->numero_dum}}</h3><p>Rentabilité: {{$dossier->rentabilite}} DH</p><hr><h5>Historique des frais:</h5><ul>@foreach($dossier->fraisRoute as $f) <li>{{$f->note}}: {{$f->montant_gasoil}} DH</li> @endforeach</ul></div> @endsection',
];

foreach ($files as $path => $content) { $zip->addFromString($path, $content); }

$zip->close();
echo "✅ [LogiTrack_Workflow_Complet.zip] prêt ! Extraire tout pour avoir le Workflow complet.\n";