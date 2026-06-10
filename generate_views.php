<?php
$baseDir = __DIR__;
$viewsDir = $baseDir . '/resources/views';
$layoutDir = $viewsDir . '/layouts';
$facturesDir = $viewsDir . '/factures';

// Création des dossiers s'ils n'existent pas
if (!is_dir($layoutDir)) mkdir($layoutDir, 0755, true);
if (!is_dir($facturesDir)) mkdir($facturesDir, 0755, true);

// 🟣 1. LE LAYOUT PRINCIPAL (layouts/app.blade.php) - Fixé pour LogiTrack Luxury
$layoutContent = <<<'EOD'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack - Enterprise Freight & Transit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .sidebar { min-height: 100vh; background-color: #0f172a; color: #ffffff; width: 260px; position: fixed; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; padding: 0.8rem 1.5rem; border-radius: 0.5rem; margin: 0.2rem 1rem; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background-color: #6b21a8; }
        .sidebar .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 0; min-height: 100vh; }
        .navbar-custom { background-color: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; }
    </style>
</head>
<body>

    <div class="sidebar shadow-sm">
        <div class="p-4">
            <h4 class="fw-bold text-white m-0 d-flex align-items-center">
                <i class="fas fa-truck-fast me-2" style="color: #c084fc;"></i>LogiTrack
            </h4>
            <small class="text-muted text-uppercase d-block mt-1" style="font-size: 10px; tracking-wider">Panel Administration v1.3</small>
        </div>
        <nav class="mt-3">
            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a class="nav-link {{ Request::is('clients*') ? 'active' : '' }}" href="/clients"><i class="fas fa-users"></i> Clients & ICE</a>
            <a class="nav-link {{ Request::is('camions*') ? 'active' : '' }}" href="/camions"><i class="fas fa-truck"></i> Parc Camions</a>
            <a class="nav-link {{ Request::is('chauffeurs*') ? 'active' : '' }}" href="/chauffeurs"><i class="fas fa-user-tie"></i> Chauffeurs</a>
            <a class="nav-link {{ Request::is('dossiers*') ? 'active' : '' }}" href="/dossiers"><i class="fas fa-folder-open"></i> Dossiers DUM</a>
            <a class="nav-link {{ Request::is('carburant*') ? 'active' : '' }}" href="/carburant"><i class="fas fa-gas-pump"></i> Carburant & Frais</a>
            <a class="nav-link {{ Request::is('maintenance*') ? 'active' : '' }}" href="/maintenance"><i class="fas fa-tools"></i> Maintenance</a>
            <a class="nav-link {{ Request::is('factures*') ? 'active' : '' }}" href="/factures" style="border: 1px solid #c084fc;"><i class="fas fa-file-invoice-dollar text-warning"></i> Factures Luxury</a>
        </nav>
        <div class="position-absolute bottom-0 w-100 p-3 bg-dark-subtle border-top border-secondary-subtle">
            <small class="text-muted d-block">Connecté: <strong>Saadia</strong></small>
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
            <span class="fw-semibold text-muted"><i class="far fa-calendar-alt me-2"></i>Espace de Travail Actif</span>
            <span class="badge bg-purple px-3 py-2 text-white" style="background-color: #6b21a8;"><i class="fas fa-shield-alt me-1"></i> Mode Connecté</span>
        </nav>
        <div class="container-fluid px-4 pb-5">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
EOD;

// 🟣 2. LA VIEW DASHBOARD (dashboard.blade.php)
$dashboardContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="mb-5">
    <h1 class="fw-bold text-dark m-0">Tableau de Bord</h1>
    <p class="text-muted m-0">Suivi analytique et opérationnel des flux de transport</p>
</div>

<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Clients Actifs</small>
                    <h3 class="fw-bold text-dark m-0 mt-2">{{ $totalClients ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #f3e8ff; color: #6b21a8;"><i class="fas fa-users fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Transit En Douane</small>
                    <h3 class="fw-bold text-dark m-0 mt-2">{{ $dossiersEnCours ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #e0f2fe; color: #0369a1;"><i class="fas fa-box-open fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted text-uppercase fw-semibold">Maintenances</small>
                    <h3 class="fw-bold text-danger m-0 mt-2">{{ $camionsEnPanne ?? 0 }}</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #fee2e2; color: #dc2626;"><i class="fas fa-tools fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm rounded-3 p-4 text-white" style="background-color: #0f172a;">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-uppercase fw-semibold" style="color: #c084fc;">Chiffre d'Affaires</small>
                    <h3 class="fw-bold m-0 mt-2">{{ number_format($totalFactures ?? 0, 2, ',', ' ') }} DH</h3>
                </div>
                <div class="p-3 rounded-3" style="background-color: #6b21a8;"><i class="fas fa-coins fa-lg"></i></div>
            </div>
        </div>
    </div>
</div>
@endsection
EOD;

// 🟣 3. LA VIEW FACTURES (factures/index.blade.php)
$facturesContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold text-dark m-0">Gestion de la Facturation</h1>
        <p class="text-muted m-0">Prestations de transit, calcul de la TVA et suivi des débours clients</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addFactureModal">
        <i class="fas fa-file-invoice-dollar me-2"></i> Émettre une Facture
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>N° Facture</th>
                    <th>Client / Dossier DUM</th>
                    <th>Prestation HT</th>
                    <th>Débours Exonérés</th>
                    <th>Total TTC</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $facture)
                <tr>
                    <td class="fw-bold text-dark" style="color: #6b21a8;">#FAC-{{ str_pad($facture->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="fw-semibold">{{ $facture->client->nom ?? 'Client Professionnel' }}</div>
                        <small class="text-muted">DUM: {{ $facture->dossier->num_dum ?? 'N/A' }}</small>
                    </td>
                    <td>{{ number_format($facture->montant_ht, 2, ',', ' ') }} DH</td>
                    <td class="text-secondary">{{ number_format($facture->debours, 2, ',', ' ') }} DH</td>
                    <td class="fw-bold" style="color: #6b21a8;">{{ number_format(($facture->montant_ht * 1.2) + $facture->debours, 2, ',', ' ') }} DH</td>
                    <td class="text-center">
                        <button class="btn btn-sm text-white px-3" style="background-color: #6b21a8;"><i class="fas fa-file-pdf me-1"></i> PDF</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-5">Aucune facture émise dans le système.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
EOD;

// Sauvegarde des fichiers physiques
file_put_contents($layoutDir . '/app.blade.php', $layoutContent);
file_put_contents($viewsDir . '/dashboard.blade.php', $dashboardContent);
file_put_contents($facturesDir . '/index.blade.php', $facturesContent);

// Compression dans un fichier ZIP
$zipPath = $baseDir . '/dossier.zip';
$zip = new ZipArchive();
if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFile($layoutDir . '/app.blade.php', 'resources/views/layouts/app.blade.php');
    $zip->addFile($viewsDir . '/dashboard.blade.php', 'resources/views/dashboard.blade.php');
    $zip->addFile($facturesDir . '/index.blade.php', 'resources/views/factures/index.blade.php');
    $zip->close();
    echo "🎁 Le fichier 'dossier.zip' a ete cree avec succes dans votre projet !\n";
    echo "✅ Les fichiers d'architecture views ont ete stabilises.\n";
} else {
    echo "❌ Erreur de creation du ZIP.\n";
}
