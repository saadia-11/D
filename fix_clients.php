<?php
$baseDir = __DIR__;
$clientDir = $baseDir . '/resources/views/clients';
$targetPath = $clientDir . '/index.blade.php';

// Créer le dossier s'il n'existe pas
if (!is_dir($clientDir)) {
    mkdir($clientDir, 0755, true);
}

$clientsContent = <<<'EOD'
@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold text-dark m-0">Gestion des Clients & ICE</h1>
        <p class="text-muted m-0">Fichier central des entreprises et suivi des identifiants fiscaux</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addClientModal">
        <i class="fas fa-user-plus me-2"></i> Ajouter un Client
    </button>
</div>

<!-- 🗂️ CARD TABLEAU DES CLIENTS -->
<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>Raison Sociale / Nom</th>
                    <th>Identifiant ICE</th>
                    <th>Téléphone</th>
                    <th>Ville / Adresse</th>
                    <th>Date d'ajout</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients ?? [] as $client)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 38px; height: 38px; background-color: #f3e8ff; color: #6b21a8 !important;">
                                {{ strtoupper(substr($client->nom, 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $client->nom }}</div>
                                <small class="text-muted">{{ $client->email ?? 'Pas d\'email' }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge font-monospace px-3 py-2 text-dark" style="background-color: #f1f5f9; border: 1px solid #cbd5e1;">
                            {{ $client->ice ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="text-secondary fw-semibold">{{ $client->telephone ?? 'N/A' }}</td>
                    <td>
                        <div class="text-dark fw-semibold">{{ $client->ville ?? 'Casablanca' }}</div>
                        <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;">{{ $client->adresse ?? '-' }}</small>
                    </td>
                    <td class="text-muted small">{{ $client->created_at ? $client->created_at->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-link text-secondary p-1 mx-1" title="Modifier"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-link text-danger p-1 mx-1" title="Supprimer"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-users-slash fa-2xl d-block mb-3 text-black-50"></i>
                        Aucun client enregistré pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
EOD;

file_put_contents($targetPath, $clientsContent);
echo "✅ Le fichier 'resources/views/clients/index.blade.php' a ete restore avec le design Luxury !\n";