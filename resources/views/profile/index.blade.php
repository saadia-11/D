@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="fw-semibold mb-2">
                        <i class="fas fa-triangle-exclamation me-2"></i>Veuillez corriger les erreurs suivantes.
                    </div>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text-dark mb-1">Profil utilisateur</h1>
                    <p class="text-muted mb-0">Gérez les informations de votre compte LogiTrack ERP.</p>
                </div>

                <span class="badge text-white px-3 py-2" style="background-color: #6b21a8;">
                    <i class="fas fa-shield-alt me-1"></i> Compte authentifié
                </span>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header text-white border-0 py-4" style="background-color: #6b21a8;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width: 56px; height: 56px;">
                            <i class="fas fa-user fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold mb-1">{{ $user->name }}</h2>
                            <p class="mb-0 opacity-75">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold text-muted">Nom</label>
                                <input id="name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold text-muted">Email</label>
                                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Date de création</label>
                                <input type="text" class="form-control bg-light" value="{{ optional($user->created_at)->format('d/m/Y H:i') ?? 'Non disponible' }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Dernière mise à jour</label>
                                <input type="text" class="form-control bg-light" value="{{ optional($user->updated_at)->format('d/m/Y H:i') ?? 'Non disponible' }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-4 py-2" style="background-color: #6b21a8;">
                                <i class="fas fa-save me-2"></i>Sauvegarder les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header text-white border-0 py-4" style="background-color: #6b21a8;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white bg-opacity-25" style="width: 56px; height: 56px;">
                            <i class="fas fa-key fs-4"></i>
                        </div>
                        <div>
                            <h2 class="h4 fw-bold mb-1">Changer le mot de passe</h2>
                            <p class="mb-0 opacity-75">Renforcez la sécurité de votre compte.</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <div class="col-12">
                                <label for="current_password" class="form-label fw-semibold text-muted">Mot de passe actuel</label>
                                <input id="current_password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autocomplete="current-password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold text-muted">Nouveau mot de passe</label>
                                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold text-muted">Confirmer le mot de passe</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="alert border-0 mt-4 mb-0" style="background-color: #f3e8ff; color: #4c1d95;">
                            <div class="fw-semibold mb-1">Sécurité du compte</div>
                            <div class="small">Votre nouveau mot de passe est validé côté serveur puis stocké sous forme hachée.</div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn text-white px-4 py-2" style="background-color: #6b21a8;">
                                <i class="fas fa-key me-2"></i>Mettre à jour le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
