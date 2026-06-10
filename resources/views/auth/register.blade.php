@extends('layouts.guest')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header text-white border-0 py-4" style="background-color: #6b21a8;">
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle" style="width: 46px; height: 46px;">
                    <i class="fas fa-user-plus"></i>
                </span>
                <div>
                    <h2 class="h4 fw-bold mb-1">Inscription</h2>
                    <p class="mb-0 opacity-75">Créez un compte pour accéder au tableau de bord.</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">Nom</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" required autofocus autocomplete="name">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Mot de passe</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirmation du mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn btn-luxury w-100 py-2">
                    Créer le compte
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Déjà inscrit ?</span>
                <a href="{{ route('login') }}" class="small fw-semibold text-luxury text-decoration-none">Connexion</a>
            </div>
        </div>
    </div>
@endsection
