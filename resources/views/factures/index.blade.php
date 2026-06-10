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
        <h1 class="fw-bold text-dark m-0">Gestion de la Facturation</h1>
        <p class="text-muted m-0">Prestations de transit, calcul de la TVA et suivi des débours clients</p>
    </div>
    <a href="{{ route('factures.create') }}" class="btn text-white px-4 py-2 rounded-3 border-0 shadow-sm" style="background-color: #6b21a8;">
        <i class="fas fa-file-invoice-dollar me-2"></i> Émettre une Facture
    </a>
</div>

<div class="card shadow-sm border-0 rounded-3 p-4 bg-white">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light text-muted small text-uppercase">
                <tr>
                    <th>N° Facture</th>
                    <th>Client / Dossier DUM</th>
                    <th>Prestations HT</th>
                    <th>Débours</th>
                    <th>Total TTC</th>
                    <th>Statut</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures ?? [] as $facture)
                <tr>
                    <td class="fw-bold" style="color: #6b21a8;">{{ $facture->numero_facture }}</td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $facture->dossier->client->nom ?? 'Client Professionnel' }}</div>
                        <small class="text-muted">DUM: {{ $facture->dossier->numero_dum ?? 'N/A' }}</small>
                    </td>
                    <td>{{ number_format($facture->montant_prestations, 2, ',', ' ') }} DH</td>
                    <td class="text-secondary">{{ number_format($facture->montant_debours, 2, ',', ' ') }} DH</td>
                    <td class="fw-bold" style="color: #6b21a8;">{{ number_format($facture->total_ttc, 2, ',', ' ') }} DH</td>
                    <td>
                        <span class="badge px-3 py-2 rounded-3" style="background-color: #e0f2fe; color: #0369a1;">
                            {{ $facture->statut_paiement ?? 'Impayée' }}
                        </span>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm text-white px-3" style="background-color: #6b21a8;">
                            <i class="fas fa-file-pdf me-1"></i> PDF
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="fas fa-file-invoice-dollar fa-2xl d-block mb-3 text-black-50"></i>
                        Aucune facture émise dans le système.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addFactureModal" tabindex="-1" aria-labelledby="addFactureModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-light border-0 p-4">
                <h5 class="modal-title fw-bold text-dark" id="addFactureModalLabel">
                    <i class="fas fa-file-invoice-dollar me-2" style="color: #6b21a8;"></i>Émettre une Facture
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addFactureForm" action="{{ route('factures.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-3">
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
                            <label for="montant_prestations" class="form-label fw-semibold text-dark">
                                <i class="fas fa-hand-holding-dollar me-2" style="color: #6b21a8;"></i>Montant prestations
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control border-1 @error('montant_prestations') is-invalid @enderror"
                                   id="montant_prestations" name="montant_prestations" placeholder="0.00" required
                                   value="{{ old('montant_prestations') }}">
                            @error('montant_prestations')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant_debours" class="form-label fw-semibold text-dark">
                                <i class="fas fa-receipt me-2" style="color: #6b21a8;"></i>Montant débours
                            </label>
                            <input type="number" step="0.01" min="0" class="form-control border-1 @error('montant_debours') is-invalid @enderror"
                                   id="montant_debours" name="montant_debours" placeholder="0.00" required
                                   value="{{ old('montant_debours') }}">
                            @error('montant_debours')
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
                <button type="submit" form="addFactureForm" class="btn text-white px-4" style="background-color: #6b21a8;">
                    <i class="fas fa-save me-2"></i>Émettre
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
