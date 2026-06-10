@extends('layouts.guest')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header text-white border-0 py-4" style="background-color: #6b21a8;">
            <div class="d-flex align-items-center gap-3">
                <span class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle" style="width: 46px; height: 46px;">
                    <i class="fas fa-lock"></i>
                </span>
                <div>
                    <h2 class="h4 fw-bold mb-1">Connexion</h2>
                    <p class="mb-0 opacity-75">Accédez à votre espace LogiTrack.</p>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-lg-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Mot de passe</label>
                    <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>

                <div class="form-check mb-4">
                    <input id="remember" type="checkbox" name="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">Se souvenir de moi</label>
                </div>

                <button type="submit" class="btn btn-luxury w-100 py-2">
                    Connexion
                </button>
            </form>

            <div class="text-center mt-4">
                <span class="text-muted small">Pas encore de compte ?</span>
                <a href="{{ route('register') }}" class="small fw-semibold text-luxury text-decoration-none">Inscription</a>
            </div>
        </div>
    </div>
@endsection
