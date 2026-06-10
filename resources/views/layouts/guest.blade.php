<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogiTrack - Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .guest-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .guest-card {
            width: 100%;
            max-width: 460px;
        }

        .btn-luxury {
            background-color: #6b21a8;
            border-color: #6b21a8;
            color: #ffffff;
        }

        .btn-luxury:hover,
        .btn-luxury:focus {
            background-color: #581c87;
            border-color: #581c87;
            color: #ffffff;
        }

        .text-luxury {
            color: #6b21a8;
        }
    </style>
</head>
<body>
    <main class="guest-shell">
        <div class="guest-card">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle text-white mb-3" style="width: 58px; height: 58px; background-color: #6b21a8;">
                    <i class="fas fa-truck-fast fs-4"></i>
                </div>
                <h1 class="h3 fw-bold mb-1">LogiTrack</h1>
                <p class="text-muted mb-0">Espace sécurisé ERP</p>
            </div>

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
