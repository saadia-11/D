<?php
// 1. Chemins des fichiers
$baseDir = __DIR__;
$targetDir = $baseDir . '/resources/views/factures';
$zipPath = $baseDir . '/dossier_factures.zip';

// 2. Créer le dossier factures s'il n'existe pas
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// 3. Le code de la vue Blade Luxury Factures
$bladeContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-dark m-0">Gestion de la Facturation</h1>
            <p class="text-muted m-0">Édition des factures clients, débours de transit et suivi financier</p>
        </div>
        <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#createFactureModal">
            <i class="fas fa-file-invoice-dollar me-2"></i> Créer une Facture
        </button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 me-3" style="background-color: #f3e8ff; color: #6b21a8;">
                        <i class="fas fa-wallet fa-2x"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold tracking-wider">Total Prestations</small>
                        <h4 class="fw-bold text-dark m-0 mt-1">H.T. (TVA 20%)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 me-3" style="background-color: #fae8ff; color: #d946ef;">
                        <i class="fas fa-hand-holding-usd fa-2x"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-semibold tracking-wider">Les Débours Transit</small>
                        <h4 class="fw-bold text-dark m-0 mt-1">Exonéré (TVA 0%)</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 p-4 text-white" style="background-color: #111827;">
                <div class="d-flex align-items-center">
                    <div class="p-3 rounded-3 me-3" style="background-color: #6b21a8;">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                    <div>
                        <small class="text-muted-light text-uppercase fw-semibold tracking-wider" style="color: #c084fc;">Chiffre d'Affaires</small>
                        <h4 class="fw-bold m-0 mt-1">Total TTC</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th>N° Facture</th>
                        <th>Client / Dossier DUM</th>
                        <th>Montant HT</th>
                        <th>Débours (Port/Douane)</th>
                        <th>Total TTC</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td class="fw-bold text-dark">
                            <span style="color: #6b21a8;">#{{ $facture->code ?? 'FAC-'.str_pad($facture->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $facture->client->nom ?? 'Client Professionnel' }}</div>
                            <small class="text-muted"><i class="fas fa-folder-open me-1"></i>Dossier: {{ $facture->dossier->num_dum ?? 'N/A' }}</small>
                        </td>
                        <td class="fw-medium text-dark">{{ number_format($facture->montant_ht, 2, ',', ' ') }} DH</td>
                        <td class="text-secondary fw-medium">{{ number_format($facture->debours, 2, ',', ' ') }} DH</td>
                        <td class="fw-bold" style="color: #6b21a8;">{{ number_format(($facture->montant_ht * 1.2) + $facture->debours, 2, ',', ' ') }} DH</td>
                        <td class="text-center">
                            <a href="{{ route('factures.show', $facture->id) }}" class="btn btn-sm btn-light border rounded-3 px-3 me-1">
                                <i class="fas fa-eye text-muted me-1"></i> Voir
                            </a>
                            <a href="#" class="btn btn-sm text-white rounded-3 px-3" style="background-color: #6b21a8;">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-file-invoice fa-2x mb-3 d-block text-muted-light"></i>
                            Aucune facture enregistrée pour le moment.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="createFactureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header p-4 border-0">
                <h5 class="modal-title fw-bold text-dark">Générer une Nouvelle Facture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('factures.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Sélectionner le Dossier Transit / Client</label>
                            <select name="dossier_id" class="form-select" required>
                                <option value="">Choisir un dossier en attente...</option>
                                @foreach($dossiersAttente ?? [] as $dossier)
                                    <option value="{{ $dossier->id }}">DUM: {{ $dossier->num_dum }} - {{ $dossier->client->nom ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Prestation de Transport HT (TVA 20%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="montant_ht" class="form-control" placeholder="Ex: 5000" required>
                                <span class="input-group-text bg-light">DH</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Montant des Débours Exonérés (Frais Port, Douane... TVA 0%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="debours" class="form-control" placeholder="Ex: 1200" default="0">
                                <span class="input-group-text bg-light">DH</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #6b21a8;">Générer Facture</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
EOD;

// 4. Écrire le fichier index.blade.php
$filePath = $targetDir . '/index.blade.php';
file_put_contents($filePath, $bladeContent);
echo "✅ 1. Le dossier et la vue 'factures/index.blade.php' ont ete crees avec succes !\n";

// 5. Créer le fichier zip pour sauvegarde
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFile($filePath, 'resources/views/factures/index.blade.php');
    $zip->close();
    echo "🎁 2. Le fichier 'dossier_factures.zip' a ete genere dans votre projet !\n";
} else {
    echo "❌ Erreur lors de la creation du zip.\n";
}