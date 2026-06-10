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
        <h1 class="fw-bold text-dark m-0">Suivi du Carburant & Recharges</h1>
        <p class="text-muted m-0">Consommation du parc, tickets de carburant et cartes de recharge</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;" data-bs-toggle="modal" data-bs-target="#addRechargeModal">
        <i class="fas fa-gas-pump me-2"></i> Enregistrer une Recharge
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>Dossier</th>
                    <th>Client</th>
                    <th>Station</th>
                    <th>Montant (DH)</th>
                    <th>Date Recharge</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recharges ?? [] as $recharge)
                <tr>
                    <td class="fw-bold text-dark">{{ $recharge->dossier->numero_dum ?? 'Dossier #'.$recharge->dossier_id }}</td>
                    <td class="fw-semibold text-secondary">{{ $recharge->dossier->client->nom ?? 'Client Standard' }}</td>
                    <td>
                        <span class="badge bg-light text-dark border px-3 py-2">
                            {{ $recharge->station ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="fw-bold" style="color: #6b21a8;">{{ number_format($recharge->montant_gasoil ?? 0, 2) }} DH</td>
                    <td class="text-muted small">
                        {{ $recharge->date_recharge ? \Carbon\Carbon::parse($recharge->date_recharge)->format('d/m/Y') : \Carbon\Carbon::parse($recharge->created_at)->format('d/m/Y') }}
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-link text-secondary p-1"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-gas-pump fa-2xl d-block mb-3 text-black-50"></i>
                        Aucun ticket de carburant enregistré pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addRechargeModal" tabindex="-1" aria-labelledby="addRechargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addRechargeModalLabel">
                    <i class="fas fa-gas-pump me-2" style="color: #6b21a8;"></i>Enregistrer une Recharge
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addRechargeForm" action="{{ route('carburant.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dossier_id" class="form-label fw-semibold text-dark">
                                <i class="fas fa-folder-open me-2" style="color: #6b21a8;"></i>Dossier de transit
                            </label>
                            <select class="form-select border-1 @error('dossier_id') is-invalid @enderror" id="dossier_id" name="dossier_id" required>
                                <option value="">Choisir un dossier</option>
                                @foreach($dossiers ?? [] as $dossier)
                                <option value="{{ $dossier->id }}" @selected(old('dossier_id') == $dossier->id)>
                                    {{ $dossier->numero_dum ?? 'Dossier #'.$dossier->id }}{{ $dossier->client ? ' - '.$dossier->client->nom : '' }}
                                </option>
                                @endforeach
                            </select>
                            @error('dossier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant_gasoil" class="form-label fw-semibold text-dark">
                                <i class="fas fa-money-bill-wave me-2" style="color: #6b21a8;"></i>Montant gasoil
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control border-1 @error('montant_gasoil') is-invalid @enderror"
                                   id="montant_gasoil" name="montant_gasoil" placeholder="0.00" required
                                   value="{{ old('montant_gasoil') }}">
                            @error('montant_gasoil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_recharge" class="form-label fw-semibold text-dark">
                                <i class="fas fa-calendar-check me-2" style="color: #6b21a8;"></i>Date recharge
                            </label>
                            <input type="date" class="form-control border-1 @error('date_recharge') is-invalid @enderror"
                                   id="date_recharge" name="date_recharge"
                                   value="{{ old('date_recharge') }}">
                            @error('date_recharge')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="station" class="form-label fw-semibold text-dark">
                                <i class="fas fa-location-dot me-2" style="color: #6b21a8;"></i>Station
                            </label>
                            <input type="text" class="form-control border-1 @error('station') is-invalid @enderror"
                                   id="station" name="station" placeholder="Station Shell, Afriquia..."
                                   value="{{ old('station') }}">
                            @error('station')
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
                <button type="submit" form="addRechargeForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
