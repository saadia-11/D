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
        <h1 class="fw-bold text-dark m-0">Gestion du Parc Camions</h1>
        <p class="text-muted m-0">Suivi de la flotte, des immatriculations et de la disponibilité du parc</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addCamionModal">
        <i class="fas fa-plus me-2"></i> Ajouter un Camion
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>Matricule</th>
                    <th>Marque / Modèle</th>
                    <th>Type de Transport</th>
                    <th>Statut Opérationnel</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($camions ?? [] as $camion)
                <tr>
                    <td class="fw-bold text-dark">{{ $camion->matricule ?? 'N/A' }}</td>
                    <td class="fw-semibold text-secondary">{{ $camion->marque ?? 'Volvo' }}</td>
                    <td><span class="badge bg-light text-dark border">National / International</span></td>
                    <td>
                        <span class="badge px-3 py-2 rounded-3" style="background-color: #e0f2fe; color: #0369a1;">
                            {{ $camion->statut ?? 'Disponible' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link text-secondary p-1"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">Aucun camion enregistré dans le parc.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addCamionModal" tabindex="-1" aria-labelledby="addCamionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addCamionModalLabel">
                    <i class="fas fa-truck me-2" style="color: #6b21a8;"></i>Ajouter un Nouveau Camion
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addCamionForm" action="{{ route('camions.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matricule" class="form-label fw-semibold text-dark">
                                <i class="fas fa-id-card me-2" style="color: #6b21a8;"></i>Matricule
                            </label>
                            <input type="text" class="form-control border-1 @error('matricule') is-invalid @enderror"
                                   id="matricule" name="matricule" placeholder="12345-A-6" required
                                   value="{{ old('matricule') }}">
                            @error('matricule')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_visite_technique" class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-check me-2" style="color: #6b21a8;"></i>Date visite technique
                            </label>
                            <input type="date" class="form-control border-1 @error('date_visite_technique') is-invalid @enderror"
                                   id="date_visite_technique" name="date_visite_technique"
                                   value="{{ old('date_visite_technique') }}">
                            @error('date_visite_technique')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="statut" class="form-label fw-semibold text-dark">
                                <i class="fas fa-circle-check me-2" style="color: #6b21a8;"></i>Statut
                            </label>
                            <select class="form-select border-1 @error('statut') is-invalid @enderror" id="statut" name="statut">
                                <option value="Disponible" @selected(old('statut', 'Disponible') === 'Disponible')>Disponible</option>
                                <option value="En Mission" @selected(old('statut') === 'En Mission')>En Mission</option>
                                <option value="En Panne" @selected(old('statut') === 'En Panne')>En Panne</option>
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
                <button type="submit" form="addCamionForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Ajouter le Camion
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
