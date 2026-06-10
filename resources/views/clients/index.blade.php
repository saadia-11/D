@extends('layouts.app')

@section('content')

<!-- ⚠️ BLOC D'AFFICHAGE DES ERREURS DE VALIDATION -->
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

<!-- ✅ BLOC D'AFFICHAGE DES MESSAGES DE SUCCÈS -->
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

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

<!-- 📋 MODALE D'AJOUT DE CLIENT -->
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addClientModalLabel">
                    <i class="fas fa-user-plus me-2" style="color: #6b21a8;"></i>Ajouter un Nouveau Client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addClientForm" action="{{ route('clients.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        <!-- Nom du Client -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-semibold text-dark">
                                <i class="fas fa-briefcase me-2" style="color: #6b21a8;"></i>Raison Sociale / Nom
                            </label>
                            <input type="text" class="form-control border-1 @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" placeholder="Société ABC..." required 
                                   value="{{ old('nom') }}">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label fw-semibold text-dark">
                                <i class="fas fa-envelope me-2" style="color: #6b21a8;"></i>Email (Optionnel)
                            </label>
                            <input type="email" class="form-control border-1 @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="contact@exemple.com" 
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-6 mb-3">
                            <label for="telephone" class="form-label fw-semibold text-dark">
                                <i class="fas fa-phone me-2" style="color: #6b21a8;"></i>Téléphone
                            </label>
                            <input type="tel" class="form-control border-1 @error('telephone') is-invalid @enderror" 
                                   id="telephone" name="telephone" placeholder="+212 6XX XXX XXX" required 
                                   value="{{ old('telephone') }}">
                            @error('telephone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ICE -->
                        <div class="col-md-6 mb-3">
                            <label for="ice" class="form-label fw-semibold text-dark">
                                <i class="fas fa-id-card me-2" style="color: #6b21a8;"></i>Identifiant ICE (Optionnel)
                            </label>
                            <input type="text" class="form-control border-1 @error('ice') is-invalid @enderror font-monospace" 
                                   id="ice" name="ice" placeholder="Identifiant Commun de l'Entreprise" 
                                   value="{{ old('ice') }}">
                            @error('ice')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-8 mb-3">
                            <label for="adresse" class="form-label fw-semibold text-dark">
                                <i class="fas fa-map-marker-alt me-2" style="color: #6b21a8;"></i>Adresse (Optionnel)
                            </label>
                            <input type="text" class="form-control border-1 @error('adresse') is-invalid @enderror" 
                                   id="adresse" name="adresse" placeholder="Rue, Quartier..." 
                                   value="{{ old('adresse') }}">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ville -->
                        <div class="col-md-4 mb-3">
                            <label for="ville" class="form-label fw-semibold text-dark">
                                <i class="fas fa-city me-2" style="color: #6b21a8;"></i>Ville (Optionnel)
                            </label>
                            <input type="text" class="form-control border-1 @error('ville') is-invalid @enderror" 
                                   id="ville" name="ville" placeholder="Casablanca" 
                                   value="{{ old('ville') }}">
                            @error('ville')
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
                <button type="submit" form="addClientForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Ajouter le Client
                </button>
            </div>
        </div>
    </div>
</div>

@endsection