<?php

use App\Http\Controllers\CamionController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DossierTransitController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FraisRouteController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/dashbord', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');

    Route::get('/camions', [CamionController::class, 'index'])->name('camions.index');
    Route::post('/camions', [CamionController::class, 'store'])->name('camions.store');

    Route::get('/chauffeurs', [ChauffeurController::class, 'index'])->name('chauffeurs.index');
    Route::post('/chauffeurs', [ChauffeurController::class, 'store'])->name('chauffeurs.store');

    Route::get('/dossiers', [DossierTransitController::class, 'index'])->name('dossiers.index');
    Route::post('/dossiers', [DossierTransitController::class, 'store'])->name('dossiers.store');

    Route::get('/carburant', [FraisRouteController::class, 'index'])->name('carburant.index');
    Route::post('/carburant', [FraisRouteController::class, 'store'])->name('carburant.store');

    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');

    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/create', [FactureController::class, 'create'])->name('factures.create');
    Route::post('/factures', [FactureController::class, 'store'])->name('factures.store');
});

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
