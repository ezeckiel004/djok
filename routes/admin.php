<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\InscriptionController as AdminInscriptionController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\FormationController as AdminFormationController;
use App\Http\Controllers\Admin\FormationSessionController as AdminFormationSessionController;
use App\Http\Controllers\Admin\StatisticsController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Admin\FormationInternationaleController as AdminFormationInternationaleController;
use App\Http\Controllers\Admin\LocationReservationController as AdminLocationReservationController;
use App\Http\Controllers\Admin\ConciergerieController as AdminConciergerieController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\PaiementController;
use App\Http\Controllers\Admin\ParticipantController as AdminParticipantController;
use App\Http\Controllers\Admin\VehicleCategoryController as AdminVehicleCategoryController;
use App\Http\Controllers\Admin\AdminElearningController;
use App\Http\Controllers\ProgrammePdfController;

Route::middleware(['auth', 'can:access-admin-dashboard'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics');

    // ==============================================
    // GESTION DES SESSIONS DE FORMATION
    // ==============================================
    Route::resource('sessions', AdminFormationSessionController::class);
    Route::post('/sessions/{session}/toggle-status', [AdminFormationSessionController::class, 'toggleStatus'])
        ->name('sessions.toggle-status');
    Route::post('/sessions/{session}/adjust-places', [AdminFormationSessionController::class, 'adjustPlaces'])
        ->name('sessions.adjust-places');
    Route::get('/sessions/{session}/export', [AdminFormationSessionController::class, 'exportParticipants'])
        ->name('sessions.export');

    // ==============================================
    // E-LEARNING
    // ==============================================
    Route::prefix('elearning')->name('elearning.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminElearningController::class, 'dashboard'])->name('dashboard');
        Route::get('/statistiques', [AdminElearningController::class, 'statistics'])->name('statistics');

        // Forfaits
        Route::get('/forfaits', [AdminElearningController::class, 'forfaits'])->name('forfaits');
        Route::get('/forfaits/create', [AdminElearningController::class, 'createForfait'])->name('forfaits.create');
        Route::post('/forfaits', [AdminElearningController::class, 'storeForfait'])->name('forfaits.store');
        Route::get('/forfaits/{id}/edit', [AdminElearningController::class, 'editForfait'])->name('forfaits.edit');
        Route::put('/forfaits/{id}', [AdminElearningController::class, 'updateForfait'])->name('forfaits.update');
        Route::delete('/forfaits/{id}', [AdminElearningController::class, 'destroyForfait'])->name('forfaits.destroy');

        // Accès
        Route::get('/acces', [AdminElearningController::class, 'acces'])->name('acces');
        Route::get('/acces/{id}', [AdminElearningController::class, 'showAcces'])->name('acces.show');
        Route::post('/acces/{id}/suspend', [AdminElearningController::class, 'suspendAcces'])->name('acces.suspend');
        Route::post('/acces/{id}/activate', [AdminElearningController::class, 'activateAcces'])->name('acces.activate');
        Route::delete('/acces/{id}', [AdminElearningController::class, 'destroyAcces'])->name('acces.destroy');
        Route::post('/acces/{id}/upload-certification', [AdminElearningController::class, 'uploadCertification'])->name('acces.upload-certification');

        // Détails QCM pour un accès spécifique
        Route::get('/acces/{acces}/qcm/{qcm}/details', [AdminElearningController::class, 'showQcmDetails'])
            ->name('acces.qcm-details');

        // Cours
        Route::get('/cours', [AdminElearningController::class, 'cours'])->name('cours');
        Route::get('/cours/create', [AdminElearningController::class, 'createCours'])->name('cours.create');
        Route::post('/cours', [AdminElearningController::class, 'storeCours'])->name('cours.store');
        Route::get('/cours/{id}/edit', [AdminElearningController::class, 'editCours'])->name('cours.edit');
        Route::put('/cours/{id}', [AdminElearningController::class, 'updateCours'])->name('cours.update');
        Route::delete('/cours/{id}', [AdminElearningController::class, 'destroyCours'])->name('cours.destroy');

        // QCM
        Route::get('/qcms', [AdminElearningController::class, 'qcms'])->name('qcms');
        Route::get('/qcms/create', [AdminElearningController::class, 'createQcm'])->name('qcms.create');
        Route::post('/qcms', [AdminElearningController::class, 'storeQcm'])->name('qcms.store');
        Route::get('/qcms/{id}', [AdminElearningController::class, 'show'])->name('qcms.show');
        Route::get('/qcms/{id}/edit', [AdminElearningController::class, 'editQcm'])->name('qcms.edit');
        Route::put('/qcms/{id}', [AdminElearningController::class, 'updateQcm'])->name('qcms.update');
        Route::delete('/qcms/{id}', [AdminElearningController::class, 'destroyQcm'])->name('qcms.destroy');

        // Sessions actives
        Route::get('/sessions/actives', [AdminElearningController::class, 'activeSessions'])->name('sessions.active');
        Route::post('/sessions/{id}/force-logout', [AdminElearningController::class, 'forceLogout'])->name('sessions.force-logout');
    });

    // Gestion des services
    Route::resource('services', AdminServiceController::class);
    Route::post('/services/{service}/toggle-status', [AdminServiceController::class, 'toggleStatus'])
        ->name('services.toggle-status');
    Route::post('/services/reorder', [AdminServiceController::class, 'reorder'])
        ->name('services.reorder');

    // Gestion des utilisateurs
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])
        ->name('users.toggle-status');

    // Gestion des inscriptions
    Route::resource('inscriptions', AdminInscriptionController::class);
    Route::post('/inscriptions/{inscription}/update-status', [AdminInscriptionController::class, 'updateStatus'])
        ->name('inscriptions.update-status');
    Route::post('/inscriptions/{inscription}/transfer', [AdminInscriptionController::class, 'transfer'])
        ->name('inscriptions.transfer');
    Route::post('/inscriptions/{inscription}/resend-email', [AdminInscriptionController::class, 'resendEmail'])
        ->name('inscriptions.resend-email');

    // Gestion des formations
    Route::resource('formations', AdminFormationController::class);
    Route::post('/formations/{formation}/sync-stripe', [AdminFormationController::class, 'syncStripe'])
        ->name('formations.sync-stripe');
    Route::post('/formations/{formation}/toggle-status', [AdminFormationController::class, 'toggleStatus'])
        ->name('formations.toggle-status');
    Route::get('/formations/{formation}/fix-slug-admin', [AdminFormationController::class, 'fixSlug'])
        ->name('formations.fix-slug-admin');
    Route::post('/formations/fix-all-slugs-admin', [AdminFormationController::class, 'fixAllSlugs'])
        ->name('formations.fix-all-slugs-admin');
    Route::get('/formations/check-slugs-admin', [AdminFormationController::class, 'checkSlugs'])
        ->name('formations.check-slugs-admin');
    Route::get('/formations/slugs-report', [AdminFormationController::class, 'slugsReport'])
        ->name('formations.slugs-report');

    // Génération PDF programme
    Route::post('/formations/{formation}/generate-pdf', [ProgrammePdfController::class, 'generateAndSave'])
        ->name('formations.generate-pdf');

    // Gestion des articles du blog
    Route::resource('articles', AdminArticleController::class);

    // Gestion des catégories de véhicules
    Route::resource('vehicle-categories', AdminVehicleCategoryController::class);

    // Profil admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AdminProfileController::class, 'edit'])->name('edit');
        Route::put('/', [AdminProfileController::class, 'update'])->name('update');
        Route::put('/password', [AdminProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/photo', [AdminProfileController::class, 'destroyPhoto'])->name('photo.destroy');
        Route::delete('/', [AdminProfileController::class, 'destroy'])->name('destroy');
        Route::post('/verification/send', [AdminProfileController::class, 'sendVerification'])
            ->name('verification.send');
    });

    // Gestion des médias
    Route::get('/formations/{formation}/media', [MediaController::class, 'index'])
        ->name('media.index');
    Route::get('/formations/{formation}/media/create', [MediaController::class, 'create'])
        ->name('media.create');
    Route::post('/formations/{formation}/media', [MediaController::class, 'store'])
        ->name('media.store');
    Route::get('/media/{media}/edit', [MediaController::class, 'edit'])
        ->name('media.edit');
    Route::put('/media/{media}', [MediaController::class, 'update'])
        ->name('media.update');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])
        ->name('media.destroy');
    Route::post('/media/reorder', [MediaController::class, 'reorder'])
        ->name('media.reorder');

    // Routes médias compatibilité
    Route::get('/formations/{formation}/media-old', [AdminFormationController::class, 'mediaIndex'])
        ->name('formations.media.index');
    Route::post('/formations/{formation}/media-old', [AdminFormationController::class, 'mediaStore'])
        ->name('formations.media.store');
    Route::delete('/formations/{formation}/media/{media}', [AdminFormationController::class, 'mediaDestroy'])
        ->name('formations.media.destroy');
    Route::post('/formations/{formation}/media/reorder', [AdminFormationController::class, 'mediaReorder'])
        ->name('formations.media.reorder');

    // Gestion des réservations (admin)
    Route::resource('reservations', AdminReservationController::class);
    Route::post('/reservations/{reservation}/update-status', [AdminReservationController::class, 'updateStatus'])
        ->name('reservations.update-status');

    // Gestion des contacts
    Route::resource('contacts', AdminContactController::class);

    // Gestion des véhicules
    Route::resource('vehicles', AdminVehicleController::class);

    // Gestion des réservations de location
    Route::resource('location-reservations', AdminLocationReservationController::class);
    Route::post(
        '/location-reservations/{locationReservation}/update-status',
        [AdminLocationReservationController::class, 'updateStatus']
    )
        ->name('location-reservations.update-status');
    Route::get(
        '/location-reservations/{id}/test-email',
        [AdminLocationReservationController::class, 'testEmail']
    )
        ->name('location-reservations.test-email');

    // Gestion des demandes de formation internationale
    Route::resource('demandes-formation-internationale', AdminFormationInternationaleController::class)
        ->parameters(['demandes-formation-internationale' => 'demande']);
    Route::post(
        '/demandes-formation-internationale/{demande}/update-status',
        [AdminFormationInternationaleController::class, 'updateStatus']
    )
        ->name('demandes-formation-internationale.update-status');
    Route::post(
        '/demandes-formation-internationale/{demande}/update-statut',
        [AdminFormationInternationaleController::class, 'updateStatut']
    )
        ->name('demandes-formation-internationale.update-statut');

    // Gestion des demandes de conciergerie
    Route::resource('conciergerie-demandes', AdminConciergerieController::class)
        ->parameters(['conciergerie-demandes' => 'conciergerie'])
        ->except(['create', 'edit']);
    Route::post(
        '/conciergerie-demandes/{conciergerie}/update-statut',
        [AdminConciergerieController::class, 'updateStatut']
    )
        ->name('conciergerie-demandes.update-statut');
    Route::post(
        '/conciergerie-demandes/{conciergerie}/envoyer-devis',
        [AdminConciergerieController::class, 'envoyerDevis']
    )
        ->name('conciergerie-demandes.envoyer-devis');
    Route::post(
        '/conciergerie-demandes/{conciergerie}/ajouter-notes',
        [AdminConciergerieController::class, 'ajouterNotes']
    )
        ->name('conciergerie-demandes.ajouter-notes');
    Route::get(
        '/conciergerie-demandes/statistiques',
        [AdminConciergerieController::class, 'statistiques']
    )
        ->name('conciergerie-demandes.statistiques');

    // Gestion des paiements (UNIFIÉ)
    Route::prefix('paiements')->name('paiements.')->group(function () {
        Route::get('/', [PaiementController::class, 'index'])->name('index');
        Route::get('/statistiques', [PaiementController::class, 'statistiques'])->name('statistiques');
        Route::get('/export', [PaiementController::class, 'export'])->name('export');
        Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show');
        Route::post('/{paiement}/refund', [PaiementController::class, 'refund'])->name('refund');

        // NOUVELLES ROUTES DE SUPPRESSION
        Route::delete('/{paiement}', [PaiementController::class, 'destroy'])->name('destroy');
        Route::delete('/bulk-destroy', [PaiementController::class, 'bulkDestroy'])->name('bulk-destroy');
    });

    // Statistiques et rapports
    Route::get('/statistiques-globales', [AdminFormationController::class, 'statistiques'])
        ->name('statistiques.globales');
    Route::get('/utilisateurs-formations', [AdminFormationController::class, 'utilisateurs'])
        ->name('utilisateurs.formations');
    Route::get('/inscriptions-admin', [AdminFormationController::class, 'inscriptions'])
        ->name('inscriptions.admin');

    // Gestion des participants aux formations
    Route::resource('participants', AdminParticipantController::class);
    Route::post('/participants/{participant}/update-status', [AdminParticipantController::class, 'updateStatus'])
        ->name('participants.update-status');
    Route::get('/participants/statistics', [AdminParticipantController::class, 'statistics'])
        ->name('participants.statistics');
    Route::get('/participants/export', [AdminParticipantController::class, 'export'])
        ->name('participants.export');

    // Gestion des newsletters
    Route::prefix('newsletter')->name('newsletter.')->group(function () {
        // Abonnés
        Route::get('/abonnes', [AdminNewsletterController::class, 'index'])->name('index');
        Route::post('/abonnes/{subscription}/toggle', [AdminNewsletterController::class, 'toggleSubscription'])
            ->name('subscriptions.toggle');
        Route::delete('/abonnes/{subscription}', [AdminNewsletterController::class, 'destroySubscription'])
            ->name('subscriptions.destroy');
        Route::get('/abonnes/export', [AdminNewsletterController::class, 'export'])->name('export');

        // Campagnes
        Route::get('/campagnes', [AdminNewsletterController::class, 'campaigns'])->name('campaigns');
        Route::get('/campagnes/create', [AdminNewsletterController::class, 'create'])->name('campaigns.create');
        Route::post('/campagnes', [AdminNewsletterController::class, 'store'])->name('campaigns.store');
        Route::get('/campagnes/{campaign}', [AdminNewsletterController::class, 'show'])->name('campaigns.show');
        Route::get('/campagnes/{campaign}/edit', [AdminNewsletterController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campagnes/{campaign}', [AdminNewsletterController::class, 'update'])->name('campaigns.update');
        Route::delete('/campagnes/{campaign}', [AdminNewsletterController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('/campagnes/{campaign}/send', [AdminNewsletterController::class, 'send'])->name('campaigns.send');
        Route::post('/campagnes/{campaign}/cancel', [AdminNewsletterController::class, 'cancel'])->name('campaigns.cancel');

        // Statistiques
        Route::get('/statistiques', [AdminNewsletterController::class, 'statistics'])->name('statistics');

        // Prévisualisation
        Route::post('/preview', [AdminNewsletterController::class, 'preview'])->name('preview');
    });
});
