<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack - Enterprise Freight & Transit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .sidebar { min-height: 100vh; background-color: #0f172a; color: #ffffff; width: 260px; position: fixed; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; padding: 0.8rem 1.5rem; border-radius: 0.5rem; margin: 0.2rem 1rem; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #ffffff; background-color: #6b21a8; }
        .sidebar .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 0; min-height: 100vh; }
        .navbar-custom { background-color: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 1rem 2rem; }
        .btn-luxury { background-color: #6b21a8; border-color: #6b21a8; color: #ffffff; }
        .btn-luxury:hover, .btn-luxury:focus { background-color: #581c87; border-color: #581c87; color: #ffffff; }
        .text-luxury { color: #6b21a8; }
    </style>
</head>
<body>
    <div class="sidebar shadow-sm">
        <div class="p-4">
            <h4 class="fw-bold text-white m-0 d-flex align-items-center">
                <i class="fas fa-truck-fast me-2" style="color: #c084fc;"></i>LogiTrack
            </h4>
            <small class="text-muted text-uppercase d-block mt-1" style="font-size: 10px;">Panel Administration v1.3</small>
        </div>

        <nav class="mt-3">
            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a class="nav-link {{ Request::is('clients*') ? 'active' : '' }}" href="/clients"><i class="fas fa-users"></i> Clients & ICE</a>
            <a class="nav-link {{ Request::is('camions*') ? 'active' : '' }}" href="/camions"><i class="fas fa-truck"></i> Parc Camions</a>
            <a class="nav-link {{ Request::is('chauffeurs*') ? 'active' : '' }}" href="/chauffeurs"><i class="fas fa-user-tie"></i> Chauffeurs</a>
            <a class="nav-link {{ Request::is('dossiers*') ? 'active' : '' }}" href="/dossiers"><i class="fas fa-folder-open"></i> Dossiers DUM</a>
            <a class="nav-link {{ Request::is('carburant*') ? 'active' : '' }}" href="/carburant"><i class="fas fa-gas-pump"></i> Carburant & Frais</a>
            <a class="nav-link {{ Request::is('maintenance*') ? 'active' : '' }}" href="/maintenance"><i class="fas fa-tools"></i> Maintenance</a>
            <a class="nav-link {{ Request::is('factures*') ? 'active' : '' }}" href="/factures" style="border: 1px solid #c084fc;"><i class="fas fa-file-invoice-dollar text-warning"></i> Factures Luxury</a>
        </nav>

        <div class="position-absolute bottom-0 w-100 p-3 bg-dark-subtle border-top border-secondary-subtle">
            @auth
                <small class="text-muted d-block mb-2">
                    Connecté: <strong>{{ Auth::user()->name }}</strong>
                </small>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-luxury w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-luxury w-100 mb-2">
                    <i class="fas fa-sign-in-alt me-1"></i> Connexion
                </a>

                <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light w-100">
                    <i class="fas fa-user-plus me-1"></i> Inscription
                </a>
            @endauth
        </div>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-custom d-flex justify-content-between align-items-center mb-4">
            <span class="fw-semibold text-muted"><i class="far fa-calendar-alt me-2"></i>Espace de Travail Actif</span>

            <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle {{ Auth::check() ? 'btn-luxury' : 'btn-outline-secondary' }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas {{ Auth::check() ? 'fa-user-shield' : 'fa-user-circle' }} me-1"></i>
                    {{ Auth::check() ? Auth::user()->name : 'Compte' }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    @auth
                        <li>
                            <span class="dropdown-item-text small text-muted">
                                Connecté en tant que<br>
                                <strong class="text-dark">{{ Auth::user()->email }}</strong>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">
                                <i class="fas fa-id-badge me-2 text-luxury"></i> Profil
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2 text-luxury"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2 text-luxury"></i> Connexion
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-2 text-luxury"></i> Inscription
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </nav>

        <div class="container-fluid px-4 pb-5">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
