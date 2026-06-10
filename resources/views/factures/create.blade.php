@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8 col-xl-7">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text-dark mb-1">Nouvelle facture</h1>
                    <p class="text-muted mb-0">Calcul automatique de la TVA et du total TTC.</p>
                </div>

                <a href="{{ route('factures.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header text-white border-0 py-4" style="background-color: #6b21a8;">
                    <div class="d-flex align-items-center gap-3">
                        <span class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle" style="width: 46px; height: 46px;">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </span>
                        <div>
                            <h2 class="h4 fw-bold mb-1">Facture client</h2>
                            <p class="mb-0 opacity-75">Sélectionnez le dossier et les montants à facturer.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Veuillez corriger les champs suivants.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('factures.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="dossier_id" class="form-label fw-semibold">Dossier de transit</label>
                            <select id="dossier_id" name="dossier_id" class="form-select @error('dossier_id') is-invalid @enderror" required>
                                <option value="">Choisir un dossier</option>
                                @foreach ($dossiers as $dossier)
                                    <option value="{{ $dossier->id }}" @selected(old('dossier_id') == $dossier->id)>
                                        {{ $dossier->numero_dum ?? 'Dossier #'.$dossier->id }}
                                        @if ($dossier->client)
                                            - {{ $dossier->client->nom }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('dossier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="montant_prestations" class="form-label fw-semibold">Montant prestations HT</label>
                                <input id="montant_prestations" type="number" name="montant_prestations" step="0.01" min="0" value="{{ old('montant_prestations') }}" class="form-control @error('montant_prestations') is-invalid @enderror" required>
                                @error('montant_prestations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="montant_debours" class="form-label fw-semibold">Montant débours</label>
                                <input id="montant_debours" type="number" name="montant_debours" step="0.01" min="0" value="{{ old('montant_debours') }}" class="form-control @error('montant_debours') is-invalid @enderror" required>
                                @error('montant_debours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_facture" class="form-label fw-semibold">Date facture</label>
                                <input id="date_facture" type="date" name="date_facture" value="{{ old('date_facture', now()->toDateString()) }}" class="form-control @error('date_facture') is-invalid @enderror" required>
                                @error('date_facture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="statut" class="form-label fw-semibold">Statut</label>
                                <select id="statut" name="statut" class="form-select @error('statut') is-invalid @enderror" required>
                                    <option value="En attente" @selected(old('statut', 'En attente') === 'En attente')>En attente</option>
                                    <option value="Payée" @selected(old('statut') === 'Payée')>Payée</option>
                                    <option value="Annulée" @selected(old('statut') === 'Annulée')>Annulée</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert border-0 mb-4" style="background-color: #f3e8ff; color: #4c1d95;">
                            <div class="fw-semibold mb-1">Calcul appliqué à l'enregistrement</div>
                            <div class="small">TVA = prestations HT x 20%, Total TTC = prestations HT + TVA + débours.</div>
                        </div>

                        <button type="submit" class="btn text-white w-100 py-2" style="background-color: #6b21a8;">
                            <i class="fas fa-save me-2"></i>Créer la facture
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
