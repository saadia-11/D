<?php
$zip = new ZipArchive();
$zipName = "LogiTrack_PDF_Module.zip";

if ($zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    die("❌ Error : Impossible de créer le ZIP.");
}

$files = [
    // 1. Controller pour gérer le PDF
    "app/Http/Controllers/PdfController.php" => '<?php namespace App\Http\Controllers; use App\Models\Facture; use Barryvdh\DomPDF\Facade\Pdf; class PdfController extends Controller { public function download($id) { $facture = Facture::findOrFail($id); $pdf = Pdf::loadView("factures.pdf", compact("facture")); return $pdf->download("facture_".$facture->id.".pdf"); } }',
    
    // 2. View Facture (Luxury PDF Template)
    "resources/views/factures/pdf.blade.php" => '<div style="font-family: Arial;">
        <h1 style="color: #6b21a8; border-bottom: 2px solid #6b21a8;">FACTURATION</h1>
        <p><strong>N° Facture:</strong> {{ $facture->id }}</p>
        <table width="100%" border="1" style="border-collapse: collapse;">
            <tr><th>Description</th><th>Montant HT</th></tr>
            <tr><td>Prestation de Transit</td><td>{{ $facture->montant_ht }} DH</td></tr>
        </table>
        <h3 style="text-align: right;">Total TTC: {{ ($facture->montant_ht * 1.2) + $facture->debours }} DH</h3>
    </div>'
];

foreach ($files as $path => $content) { $zip->addFromString($path, $content); }

$zip->close();
echo "✅ [LogiTrack_PDF_Module.zip] prêt ! Extraire pour compléter le module PDF.\n";