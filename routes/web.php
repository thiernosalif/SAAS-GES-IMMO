<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProprietaireController;
use App\Http\Controllers\BienController;
use App\Http\Controllers\LocataireController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\ComptabiliteController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\ParametresController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\SuperAdmin\AgencyController;
use App\Http\Controllers\SuperAdmin\SuperDashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperUserController;

Route::get('/', fn () => redirect()->route('dashboard'));

// ─── Routes super-admin ────────────────────────────────────────────────────
Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth', 'role:super_admin'])
    ->group(function () {
        Route::get('/dashboard', [SuperDashboardController::class, 'index'])->name('dashboard');
        Route::resource('agencies', AgencyController::class);
        Route::resource('users', SuperUserController::class);
    });

// ─── Routes agence ─────────────────────────────────────────────────────────
Route::middleware(['auth', 'set_tenant', 'agency_active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('proprietaires', ProprietaireController::class);
    Route::resource('biens', BienController::class);
    Route::resource('locataires', LocataireController::class);
    Route::resource('contrats', ContratController::class)->except(['destroy']);
    Route::patch('contrats/{contrat}/resilier', [ContratController::class, 'resilier'])->name('contrats.resilier');

    Route::resource('paiements', PaiementController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('paiements/{paiement}/recu', [PaiementController::class, 'recu'])->name('paiements.recu');

    Route::resource('situations', SituationController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('situations/{situation}/pdf', [SituationController::class, 'pdf'])->name('situations.pdf');

    Route::get('comptabilite', [ComptabiliteController::class, 'index'])->name('comptabilite.index');
    Route::post('comptabilite', [ComptabiliteController::class, 'store'])->name('comptabilite.store');

    Route::resource('reclamations', ReclamationController::class)->except(['destroy']);
    Route::get('rapports/ca', [RapportController::class, 'ca'])->name('rapports.ca');

    Route::middleware('role:agency_admin')->group(function () {
        Route::get('parametres', [ParametresController::class, 'edit'])->name('parametres.edit');
        Route::put('parametres', [ParametresController::class, 'update'])->name('parametres.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::post('/profil/langue', [ProfileController::class, 'langue'])->name('profil.langue');
    });
});

require __DIR__ . '/auth.php';
