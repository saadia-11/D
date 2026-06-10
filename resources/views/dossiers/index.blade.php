@extends('layouts.app')

@section('content')
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Erreurs de validation</h4>
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="fw-bold text-dark m-0">Gestion des Dossiers de Transit (DUM)</h1>
        <p class="text-muted m-0">Suivi logistique, douanier et affectation des chauffeurs</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addDossierModal">
        <i class="fas fa-folder-plus me-2"></i> Ouvrir un Dossier Transit
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th style="min-width: 100px;">N° DUM</th>
                    <th>Client / Entreprise</th>
                    <th>Transport</th>
                    <th>Provenance / Destination</th>
                    <th>Valeur Déclarée</th>
                    <th>Chauffeur Affecté</th>
                    <th>Statut Logistique</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dossiers ?? [] as $dossier)
                <tr>
                    <td class="fw-bold text-dark">
                        {{ $dossier->numero_dum ?? 'DUM-'.date('Y').'-'.$dossier->id }}
                    </td>
                    
                    <td>
                        <div class="fw-semibold text-dark">{{ $dossier->client->nom ?? 'Client Standard' }}</div>
                        <small class="text-muted">ICE: {{ $dossier->client->ice ?? 'N/A' }}</small>
                    </td>
                    
                    <td>
                        <span class="badge bg-light text-dark border px-2 py-1 text-uppercase">
                            <i class="fas fa-truck me-1 text-muted"></i> {{ $dossier->mode_transport ?? 'Routier' }}
                        </span>
                    </td>
                    
                    <td class="text-secondary small fw-medium">
                        {{ $dossier->provenance_destination ?? 'Port Tanger Med' }}
                    </td>
                    
                    <td class="fw-bold text-dark">
                        {{ number_format($dossier->getAttribute('valeur_declarée') ?? 0, 2) }} DH
                    </td>
                    
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold me-2" 
                                 style="width: 28px; height: 28px; background-color: #f3e8ff; color: #6b21a8; font-size: 11px;">
                                {{ strtoupper(substr($dossier->chauffeur_nom ?? 'C', 0, 1)) }}
                            </div>
                            <span class="small fw-semibold text-dark">{{ $dossier->chauffeur_nom ?? 'Non assigné' }}</span>
                        </div>
                    </td>
                    
                    <td>
                        <span class="badge px-3 py-2 rounded-3" style="background-color: #e0f2fe; color: #0369a1;">
                            {{ $dossier->statut ?? 'En Cours' }}
                        </span>
                    </td>
                    
                    <td class="text-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-link text-secondary p-1 mx-1" title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-link p-1 mx-1" style="color: #6b21a8;" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <div class="my-3">
                            <i class="fas fa-folder-open fa-2xl d-block mb-3 text-black-50" style="opacity: 0.2;"></i>
                            <span>Aucun dossier de transit disponible.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addDossierModal" tabindex="-1" aria-labelledby="addDossierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addDossierModalLabel">
                    <i class="fas fa-folder-plus me-2" style="color: #6b21a8;"></i>Ouvrir un Nouveau Dossier
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addDossierForm" action="{{ route('dossiers.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-building me-2" style="color: #6b21a8;"></i>Client
                            </label>
                            <select class="form-select border-1 @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                <option value="">Choisir un client</option>
                                @foreach($clients ?? [] as $client)
                                <option value="{{ $client->id }}" @selected(old('client_id') == $client->id)>
                                    {{ $client->nom }}{{ $client->ice ? ' - ICE: '.$client->ice : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_dum" class="form-label fw-semibold text-dark">
                                <i class="fas fa-file-invoice me-2" style="color: #6b21a8;"></i>N° DUM
                            </label>
                            <input type="text" class="form-control border-1 @error('numero_dum') is-invalid @enderror"
                                   id="numero_dum" name="numero_dum" placeholder="DUM-2026-001"
                                   value="{{ old('numero_dum') }}">
                            @error('numero_dum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="valeur_declarée" class="form-label fw-semibold text-dark">
                                <i class="fas fa-money-bill-wave me-2" style="color: #6b21a8;"></i>Valeur déclarée
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control border-1 @error('valeur_declarée') is-invalid @enderror"
                                   id="valeur_declarée" name="valeur_declarée" placeholder="0.00"
                                   value="{{ old('valeur_declarée') }}">
                            @error('valeur_declarée')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label fw-semibold text-dark">
                                <i class="fas fa-circle-check me-2" style="color: #6b21a8;"></i>Statut
                            </label>
                            <select class="form-select border-1 @error('statut') is-invalid @enderror" id="statut" name="statut">
                                <option value="Créé" @selected(old('statut', 'Créé') === 'Créé')>Créé</option>
                                <option value="En Douane" @selected(old('statut') === 'En Douane')>En Douane</option>
                                <option value="Dédouané" @selected(old('statut') === 'Dédouané')>Dédouané</option>
                                <option value="En Cours de Livraison" @selected(old('statut') === 'En Cours de Livraison')>En Cours de Livraison</option>
                                <option value="Livré" @selected(old('statut') === 'Livré')>Livré</option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 p-4 bg-light">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Annuler
                </button>
                <button type="submit" form="addDossierForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Ouvrir le Dossier
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
