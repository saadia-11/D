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
        <h1 class="fw-bold text-dark m-0">Gestion des Chauffeurs</h1>
        <p class="text-muted m-0">Suivi des conducteurs, des permis de conduire et assignations des trajets</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addChauffeurModal">
        <i class="fas fa-user-plus me-2"></i> Ajouter un Chauffeur
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>Nom Complet</th>
                    <th>N° Téléphone</th>
                    <th>Type Permis</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($chauffeurs ?? [] as $chauffeur)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 38px; height: 38px; background-color: #f3e8ff; color: #6b21a8 !important;">
                                {{ strtoupper(substr($chauffeur->nom ?? 'C', 0, 1)) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $chauffeur->nom ?? 'Chauffeur' }} {{ $chauffeur->prenom ?? '' }}</div>
                                <small class="text-muted">Cin: {{ $chauffeur->cin ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="text-secondary fw-semibold">{{ $chauffeur->telephone ?? 'N/A' }}</td>
                    <td><span class="badge bg-light text-dark border">Permis C / E</span></td>
                    <td>
                        <span class="badge px-3 py-2 rounded-3" style="background-color: #e0f2fe; color: #0369a1;">
                            {{ $chauffeur->statut_dispo ?? 'Disponible' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link text-secondary p-1"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">Aucun chauffeur enregistré pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addChauffeurModal" tabindex="-1" aria-labelledby="addChauffeurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addChauffeurModalLabel">
                    <i class="fas fa-user-plus me-2" style="color: #6b21a8;"></i>Ajouter un Nouveau Chauffeur
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addChauffeurForm" action="{{ route('chauffeurs.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user me-2" style="color: #6b21a8;"></i>Nom
                            </label>
                            <input type="text" class="form-control border-1 @error('nom') is-invalid @enderror"
                                   id="nom" name="nom" placeholder="Nom du chauffeur" required
                                   value="{{ old('nom') }}">
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="prenom" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user me-2" style="color: #6b21a8;"></i>Prénom
                            </label>
                            <input type="text" class="form-control border-1 @error('prenom') is-invalid @enderror"
                                   id="prenom" name="prenom" placeholder="Prénom du chauffeur"
                                   value="{{ old('prenom') }}">
                            @error('prenom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="col-md-6 mb-3">
                            <label for="matricule_camion" class="form-label fw-semibold text-dark">
                                <i class="fas fa-truck me-2" style="color: #6b21a8;"></i>Matricule camion
                            </label>
                            <input type="text" class="form-control border-1 @error('matricule_camion') is-invalid @enderror"
                                   id="matricule_camion" name="matricule_camion" placeholder="12345-A-6" required
                                   value="{{ old('matricule_camion') }}">
                            @error('matricule_camion')
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
                <button type="submit" form="addChauffeurForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Ajouter le Chauffeur
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
