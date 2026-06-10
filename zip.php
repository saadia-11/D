<?php
// 1. Chemins des fichiers
$baseDir = __DIR__;
$targetDir = $baseDir . '/resources/views/camions';
$zipPath = $baseDir . '/dossier.zip';

// 2. Créer le dossier camions s'il n'existe pas
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

// 3. Le code de la vue Blade Luxury
$bladeContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold text-dark m-0">Gestion du Parc Camions</h1>
            <p class="text-muted m-0">Suivi technique, administratif et alertes d'expiration des documents</p>
        </div>
        <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addCamionModal">
            <i class="fas fa-plus me-2"></i> Ajouter un Véhicule
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #f3e8ff; color: #6b21a8;">
            🍇 {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3 p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th>Camion / Matricule</th>
                        <th>Marque & Tonnage</th>
                        <th>Assurance</th>
                        <th>Vignette</th>
                        <th>Contrôle Technique</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($camions as $camion)
                    <tr>
                        <td class="fw-bold text-dark"><i class="fas fa-truck text-muted me-2"></i>{{ $camion->matricule }}</td>
                        <td>
                            <div class="fw-semibold">{{ $camion->marque }}</div>
                            <small class="text-muted">{{ $camion->tonnage }}</small>
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $camion->date_assurance }}</div>
                            @if($camion->jours_assurance <= 15)
                                <span class="badge bg-danger-subtle text-danger small">⚠️ Expire dans {{ $camion->jours_assurance }} jours</span>
                            @else
                                <span class="badge bg-success-subtle text-success small">✅ Valide</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $camion->date_vignette }}</div>
                            @if($camion->jours_vignette <= 15)
                                <span class="badge bg-danger-subtle text-danger small">⚠️ Expire dans {{ $camion->jours_vignette }} jours</span>
                            @else
                                <span class="badge bg-success-subtle text-success small">✅ Valide</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-medium">{{ $camion->date_controle_technique }}</div>
                            @if($camion->jours_controle <= 15)
                                <span class="badge bg-danger-subtle text-danger small">⚠️ Expire dans {{ $camion->jours_controle }} jours</span>
                            @else
                                <span class="badge bg-success-subtle text-success small">✅ Valide</span>
                            @endif
                        </td>
                        <td><span class="badge bg-purple px-2 py-1" style="background-color: #f3e8ff; color: #6b21a8;">{{ $camion->statut }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Aucun camion enregistré dans le parc.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCamionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header p-4 border-0">
                <h5 class="modal-title fw-bold text-dark">Ajouter un Nouveau Camion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('camions.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Matricule</label>
                            <input type="text" name="matricule" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Marque</label>
                            <input type="text" name="marque" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold text-muted">Tonnage</label>
                            <input type="text" name="tonnage" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Date d'échéance Assurance</label>
                            <input type="date" name="date_assurance" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Date d'échéance Vignette</label>
                            <input type="date" name="date_vignette" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small fw-semibold text-muted">Date Contrôle Technique</label>
                            <input type="date" name="date_controle_technique" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-4 border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn text-white px-4" style="background-color: #6b21a8;">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
EOD;

// 4. Sauvegarder index.blade.php
$filePath = $targetDir . '/index.blade.php';
file_put_contents($filePath, $bladeContent);
echo "✅ 1. Le dossier et la vue 'index.blade.php' ont ete crees !\n";

// 5. Générer le fichier dossier.zip
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFile($filePath, 'resources/views/camions/index.blade.php');
    $zip->close();
    echo "🎁 2. Le fichier 'dossier.zip' a ete genere avec succes dans votre projet !\n";
} else {
    echo "❌ Erreur lors de la creation du zip.\n";
}