<?php

use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\FormationController;
use App\Http\Controllers\Client\LocationReservationController;
use App\Http\Controllers\Client\ReservationController;
use App\Http\Controllers\Client\ConciergerieController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\Client\InvoiceController;
use App\Http\Controllers\Client\ClientElearningController;
use App\Http\Controllers\ContactController;

Route::middleware(['auth', 'can:access-client-dashboard'])->prefix('client')->name('client.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/recent-formations', [DashboardController::class, 'getRecentFormations'])->name('dashboard.recent-formations');
    Route::get('/dashboard/recommended-formations', [DashboardController::class, 'getRecommendedFormations'])->name('dashboard.recommended-formations');
    Route::get('/dashboard/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('dashboard.recent-activity');

    // ==============================================
    // FORMATIONS - SYSTÈME COMPLET
    // ==============================================
    Route::prefix('formations')->name('formations.')->group(function () {
        // Mes formations
        Route::get('/', [FormationController::class, 'index'])->name('index');

        // Catalogue des formations disponibles
        Route::get('/catalogue', [FormationController::class, 'catalogue'])->name('catalogue');
        Route::get('/catalogue/{formation}', [FormationController::class, 'showCatalogueDetails'])->name('catalogue.details');

        // Inscription aux formations
        Route::get('/inscrire/{formation}', [FormationController::class, 'inscrire'])->name('inscrire');
        Route::post('/inscrire/{formation}/store', [FormationController::class, 'storeInscription'])->name('inscrire.store');

        // Paiement e-learning
        Route::post('/{formation}/create-payment', [FormationController::class, 'createPayment'])->name('create-payment');
        Route::get('/paiement/success', [FormationController::class, 'paymentSuccess'])->name('payment.success');
        Route::get('/paiement/cancel', [FormationController::class, 'paymentCancel'])->name('payment.cancel');

        // Gestion des formations achetées
        Route::get('/{id}', [FormationController::class, 'show'])->name('show');
        Route::get('/{id}/acceder', [FormationController::class, 'acceder'])->name('acceder');
        Route::get('/{id}/compte-rendu', [FormationController::class, 'compteRendu'])->name('compte-rendu');

        // Téléchargement des documents
        Route::get('/{userFormation}/download/{media}', [FormationController::class, 'downloadMedia'])->name('download.media');
    });

    // ==============================================
    // E-LEARNING POUR CLIENTS CONNECTÉS
    // ==============================================
    Route::prefix('elearning')->name('elearning.')->group(function () {
        // Liste des forfaits e-learning
        Route::get('/', [ClientElearningController::class, 'index'])->name('index');

        // Achat de forfait e-learning
        Route::get('/forfait/{forfaitSlug}', [ClientElearningController::class, 'acheter'])->name('acheter');
        Route::post('/forfait/{forfaitSlug}/paiement', [ClientElearningController::class, 'processPayment'])->name('process-payment');

        // Dashboard e-learning (salle virtuelle client)
        Route::get('/dashboard', [ClientElearningController::class, 'dashboard'])->name('dashboard');
        Route::get('/logout', [ClientElearningController::class, 'logout'])->name('logout');

        // Contenu e-learning (cours et QCM)
        Route::get('/cours/{coursId}', [ClientElearningController::class, 'showCours'])->name('cours.show');
        Route::post('/cours/{coursId}/complete', [ClientElearningController::class, 'completeCours'])->name('cours.complete');
        Route::get('/qcm/{qcmId}', [ClientElearningController::class, 'showQcm'])->name('qcm.show');
        Route::post('/qcm/{qcmId}/submit', [ClientElearningController::class, 'submitQcm'])->name('qcm.submit');
    });

    // ==============================================
    // LOCATION RESERVATIONS
    // ==============================================
    Route::prefix('location-reservations')->name('location-reservations.')->group(function () {
        Route::get('/', [LocationReservationController::class, 'index'])->name('index');
        Route::get('/create', [LocationReservationController::class, 'create'])->name('create');
        Route::post('/', [LocationReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [LocationReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [LocationReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [LocationReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [LocationReservationController::class, 'destroy'])->name('destroy');
    });

    // ==============================================
    // RESERVATIONS VTC
    // ==============================================
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [ReservationController::class, 'edit'])->name('edit');
        Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
    });

    // ==============================================
    // CONCIERGERIE
    // ==============================================
    Route::prefix('conciergerie-demandes')->name('conciergerie-demandes.')->group(function () {
        // CRUD de base
        Route::get('/', [ConciergerieController::class, 'index'])->name('index');
        Route::get('/create', [ConciergerieController::class, 'create'])->name('create');
        Route::post('/', [ConciergerieController::class, 'store'])->name('store');
        Route::get('/{id}', [ConciergerieController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ConciergerieController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ConciergerieController::class, 'update'])->name('update');
        Route::delete('/{id}', [ConciergerieController::class, 'destroy'])->name('destroy');

        // Filtrage et recherche
        Route::post('/filtrer', [ConciergerieController::class, 'filtrer'])->name('filtrer');

        // Statistiques
        Route::get('/statistiques', [ConciergerieController::class, 'statistiques'])->name('statistiques');

        // Actions sur les devis
        Route::post('/{id}/demander-nouveau-devis', [ConciergerieController::class, 'demanderNouveauDevis'])
            ->name('demander-nouveau-devis');
        Route::post('/{id}/confirmer-devis', [ConciergerieController::class, 'confirmerDevis'])
            ->name('confirmer-devis');

        // Export PDF
        Route::get('/{id}/export-pdf', [ConciergerieController::class, 'exportPdf'])
            ->name('export-pdf');
    });

    // ==============================================
    // FACTURES / INVOICES
    // ==============================================
    Route::prefix('factures')->name('factures.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/{paiement}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{paiement}/download', [InvoiceController::class, 'download'])->name('download');
    });

    // ==============================================
    // PROFIL CLIENT
    // ==============================================
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ==============================================
    // PARAMÈTRES
    // ==============================================
    Route::get('/parametres', [ProfileController::class, 'settings'])->name('settings');
    Route::put('/parametres', [ProfileController::class, 'updateSettings'])->name('settings.update');

    // ==============================================
    // SUPPORT CLIENT
    // ==============================================
    Route::get('/support', function () {
        return view('client.dashboard.support');
    })->name('support');
    Route::post('/support', [ContactController::class, 'storeSupport'])->name('support.store');
});
