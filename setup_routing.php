<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CamionController;
// زيدي كاع الـ Controllers اللي عندك هنا...

// 1. Dashboard
Route::get('/', function () { return view('dashboard'); })->name('dashboard');

// 2. Gestion des Clients (هنا فين كان الخلل)
Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
Route::post('/clients', [ClientController::class, 'store'])->name('clients.store'); // للإضافة
Route::put('/clients/{id}', [ClientController::class, 'update'])->name('clients.update'); // للتعديل
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy'); // للحذف

// 3. Gestion du Parc Camions
Route::get('/camions', [CamionController::class, 'index'])->name('camions.index');
Route::post('/camions', [CamionController::class, 'store'])->name('camions.store');

// 4. ... (نفس الشيء ديريه للـ Chauffeurs و Dossiers و Factures)