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
        <h1 class="fw-bold text-dark m-0">Maintenance & Entretien</h1>
        <p class="text-muted m-0">Suivi des réparations, vidanges, visites techniques et états des camions</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
        <i class="fas fa-tools me-2"></i> Planifier un Entretien
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>Camion (Matricule)</th>
                    <th>Type d'entretien</th>
                    <th>Montant estimé</th>
                    <th>Date prévue</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances ?? [] as $maintenance)
                <tr>
                    <td class="fw-bold text-dark">{{ $maintenance->camion->matricule ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            {{ $maintenance->type_entretien ?? $maintenance->type_panne ?? 'Vidange' }}
                        </span>
                    </td>
                    <td class="fw-bold text-dark">{{ number_format($maintenance->montant_facture ?? 0, 2) }} DH</td>
                    <td class="text-muted small">
                        {{ \Carbon\Carbon::parse($maintenance->date_prevue ?? $maintenance->date_reparation ?? now())->format('d/m/Y') }}
                    </td>
                    <td>
                        <span class="badge px-3 py-2 rounded-3" style="background-color: #fef3c7; color: #d97706;">
                            {{ $maintenance->statut ?? 'En Cours' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link text-secondary p-1"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-tools fa-2xl d-block mb-3 text-black-50"></i>
                        Aucune opération de maintenance enregistrée pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-labelledby="addMaintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addMaintenanceModalLabel">
                    <i class="fas fa-tools me-2" style="color: #6b21a8;"></i>Planifier un Entretien
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addMaintenanceForm" action="{{ route('maintenance.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="camion_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-truck me-2" style="color: #6b21a8;"></i>Camion
                            </label>
                            <select class="form-select border-1 @error('camion_id') is-invalid @enderror" id="camion_id" name="camion_id" required>
                                <option value="">Choisir un camion</option>
                                @foreach($camions ?? [] as $camion)
                                <option value="{{ $camion->id }}" @selected(old('camion_id') == $camion->id)>
                                    {{ $camion->matricule }} - {{ $camion->statut ?? 'Disponible' }}
                                </option>
                                @endforeach
                            </select>
                            @error('camion_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type_entretien" class="form-label fw-semibold text-dark">
                                <i class="fas fa-screwdriver-wrench me-2" style="color: #6b21a8;"></i>Type d'entretien
                            </label>
                            <input type="text" class="form-control border-1 @error('type_entretien') is-invalid @enderror"
                                   id="type_entretien" name="type_entretien" placeholder="Vidange, pneus, visite technique..." required
                                   value="{{ old('type_entretien') }}">
                            @error('type_entretien')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_prevue" class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-check me-2" style="color: #6b21a8;"></i>Date prévue
                            </label>
                            <input type="date" class="form-control border-1 @error('date_prevue') is-invalid @enderror"
                                   id="date_prevue" name="date_prevue" required
                                   value="{{ old('date_prevue') }}">
                            @error('date_prevue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant_facture" class="form-label fw-semibold text-dark">
                                <i class="fas fa-money-bill-wave me-2" style="color: #6b21a8;"></i>Montant estimé
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control border-1 @error('montant_facture') is-invalid @enderror"
                                   id="montant_facture" name="montant_facture" placeholder="0.00"
                                   value="{{ old('montant_facture') }}">
                            @error('montant_facture')
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
                <button type="submit" form="addMaintenanceForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Planifier
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
