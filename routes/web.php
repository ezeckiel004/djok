<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\ProgrammePdfController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LocationReservationController;
use App\Http\Controllers\ConciergerieController;
use App\Http\Controllers\FormationInternationaleController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ElearningController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', fn() => view('about'))->name('about');

// ==============================================
// E-LEARNING - Routes CORRIGÉES
// ==============================================

// Route pour la liste des forfaits e-learning
Route::get('/formation/elearning', [ElearningController::class, 'index'])->name('elearning.index');

// Routes d'achat e-learning
Route::get('/elearning/forfait/{forfaitSlug}', [ElearningController::class, 'acheter'])->name('elearning.acheter');
Route::post('/elearning/forfait/{forfaitSlug}/paiement', [ElearningController::class, 'processPayment'])->name('elearning.process-payment');

// CORRECTION : Route pour le succès du paiement e-learning (nom de méthode corrigé)
Route::get('/elearning/paiement/success', [ElearningController::class, 'paymentSuccess'])->name('elearning.payment.success');


// Salle virtuelle e-learning
Route::get('/elearning/salle', [ElearningController::class, 'salle'])->name('elearning.salle');
Route::post('/elearning/login', [ElearningController::class, 'login'])->name('elearning.login');
Route::get('/elearning/virtual-room', [ElearningController::class, 'virtualRoom'])->name('elearning.virtual-room');
Route::get('/elearning/logout', [ElearningController::class, 'logout'])->name('elearning.logout');

// Contenu e-learning
Route::get('/elearning/cours/{coursId}', [ElearningController::class, 'showCours'])->name('elearning.cours.show');
Route::post('/elearning/cours/{coursId}/complete', [ElearningController::class, 'completeCours'])->name('elearning.cours.complete');
Route::get('/elearning/qcm/{qcmId}', [ElearningController::class, 'showQcm'])->name('elearning.qcm.show');
Route::post('/elearning/qcm/{qcmId}/submit', [ElearningController::class, 'submitQcm'])->name('elearning.qcm.submit');

// ==============================================
// Formations
// ==============================================
Route::get('/formation', [FormationController::class, 'index'])->name('formation');
Route::get('/formation/{slug}', [FormationController::class, 'show'])->name('formation.show');

// PDF Programme des formations
Route::get('/formation/{id}/programme-pdf', [ProgrammePdfController::class, 'show'])
    ->name('formation.programme.pdf.show');

Route::get('/formation/{id}/programme-pdf/download', [ProgrammePdfController::class, 'download'])
    ->name('formation.programme.pdf.download');

// Location
Route::get('/location', [LocationController::class, 'index'])->name('location');
Route::get('/vehicule/{id}', [LocationController::class, 'showVehicleDetails'])->name('vehicle.details');

// Pages statiques
Route::get('/vtc-transport', fn() => view('vtc-transport'))->name('vtc-transport');
Route::get('/conciergerie', fn() => view('conciergerie'))->name('conciergerie');
Route::get('/formation-international', fn() => view('formation-international'))->name('formation.international');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/categorie/{category}', [BlogController::class, 'category'])->name('blog.category');

// Pages d'information
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/espaceclient', fn() => view('espaceclient'))->name('espaceclient');
Route::get('/performance', fn() => view('performance'))->name('performance');
Route::get('/reclamation', fn() => view('reclamation'))->name('reclamation');

// Formulaires publics
Route::post('/formation-internationale', [FormationInternationaleController::class, 'store'])
    ->name('formation-internationale.store');

Route::post('/conciergerie', [ConciergerieController::class, 'store'])->name('conciergerie.store');
Route::get('/conciergerie/suivi/{reference}', [ConciergerieController::class, 'suivi'])->name('conciergerie.suivi');

// Réservations VTC - Routes principales
Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation');
Route::post('/reservation', [ReservationController::class, 'submit'])->name('reservation.submit');

// API pour calculer le prix (AJAX)
Route::post('/reservation/calculate-price', [ReservationController::class, 'calculatePriceApi'])
    ->name('reservation.calculate-price');

// Réservations location
Route::get('/reservation-location', [LocationReservationController::class, 'create'])
    ->name('location.reservation.create');
Route::post('/reservation-location', [LocationReservationController::class, 'store'])
    ->name('location.reservation.store');
Route::get('/reservation-location/confirmation/{reference}', [LocationReservationController::class, 'confirmation'])
    ->name('location.reservation.confirmation');
Route::post('/reservation-location/check-availability', [LocationReservationController::class, 'checkAvailability'])
    ->name('location.reservation.check.availability');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');
Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])
    ->name('newsletter.confirm');

// Tracking newsletter
Route::get('/newsletter/track/open/{campaign}/{subscription}/{token}', function ($campaignId, $subscriptionId, $token) {
    $campaign = \App\Models\NewsletterCampaign::find($campaignId);
    $subscription = \App\Models\NewsletterSubscription::find($subscriptionId);

    if ($campaign && $subscription && md5($subscription->email . $campaignId) === $token) {
        $campaign->increment('opened_count');
        \App\Models\NewsletterLog::create([
            'campaign_id' => $campaign->id,
            'subscription_id' => $subscription->id,
            'email' => $subscription->email,
            'action' => 'opened',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
    return response($image)->header('Content-Type', 'image/png');
})->name('newsletter.track.open');

Route::get('/newsletter/track/click/{campaign}/{subscription}/{token}', function ($campaignId, $subscriptionId, $token) {
    $campaign = \App\Models\NewsletterCampaign::find($campaignId);
    $subscription = \App\Models\NewsletterSubscription::find($subscriptionId);
    $url = request()->query('url');

    if ($campaign && $subscription && md5($subscription->email . $campaignId) === $token && $url) {
        $campaign->increment('clicked_count');
        \App\Models\NewsletterLog::create([
            'campaign_id' => $campaign->id,
            'subscription_id' => $subscription->id,
            'email' => $subscription->email,
            'action' => 'clicked',
            'data' => ['url' => $url],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        return redirect($url);
    }
    abort(404);
})->name('newsletter.track.click');

// Routes pour les PDF légaux/certifications
Route::get('/pdf/arrete-modificatif-07-11-2025', function () {
    $path = public_path('arrt modificatif du 07-11-2025.pdf');

    if (!file_exists($path)) {
        abort(404, 'Fichier PDF non trouvé');
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="arrete-modificatif-07-11-2025.pdf"'
    ]);
})->name('pdf.arrete-modificatif');

// Contact
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Pages légales
Route::get('/cgv', fn() => view('cgv'))->name('cgv');
Route::get('/cgu', fn() => view('cgu'))->name('cgu');
Route::get('/rgpd', fn() => view('rgpd'))->name('rgpd');
Route::get('/mentions-legales', fn() => view('mentions-legales'))->name('mentions-legales');

// ==============================================
// PAIEMENTS UNIFIÉS - Routes publiques
// ==============================================
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/confirmation/{reference}', [PaymentController::class, 'confirmation'])->name('payment.confirmation');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// Route de suivi conciergerie
Route::get('/conciergerie/suivi/{reference}', [\App\Http\Controllers\Client\ConciergerieController::class, 'suivi'])
    ->name('conciergerie.suivi');

// ==============================================
// REDIRECTION POUR ANCIENS LIENS /mes-formations
// ==============================================
Route::get('/mes-formations', function () {
    if (auth()->check()) {
        return redirect()->route('client.formations.index');
    }
    return redirect()->route('login');
})->name('formations.mes-formations-redirect');

// ==============================================
// FORMATIONS UTILISATEUR (authentification requise)
// ==============================================
Route::middleware(['auth'])->group(function () {
    // Routes d'achat/inscription seulement
    Route::get('/formation/{id}/inscrire-presentiel', [FormationController::class, 'inscrirePresentiel'])
        ->name('formation.inscrire.presentiel');
    Route::post('/formation/{id}/inscrire-presentiel', [FormationController::class, 'storeInscriptionPresentiel'])
        ->name('formation.inscrire.presentiel.store');
    Route::get('/formation/{id}/acheter-elearning', [FormationController::class, 'acheterElearning'])
        ->name('formation.acheter.elearning');
    Route::post('/formation/{id}/create-payment-session', [FormationController::class, 'createPaymentSession'])
        ->name('formation.create.payment.session');

    // Routes de redirection pour les formations (vers PaymentController unifié)
    Route::get('/formation/payment/success', function (Illuminate\Http\Request $request) {
        return redirect()->route('payment.success', ['session_id' => $request->get('session_id')]);
    })->name('formation.payment.success');

    Route::get('/formation/payment/cancel', function () {
        return redirect()->route('payment.cancel');
    })->name('formation.payment.cancel');
});

// ==============================================
// GESTION DES SLUGS (admin uniquement)
// ==============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/formations/{formation}/fix-slug', [FormationController::class, 'fixSlug'])
        ->name('formations.fix-slug');
    Route::post('/formations/fix-all-slugs', [FormationController::class, 'fixAllSlugs'])
        ->name('formations.fix-all-slugs');
    Route::get('/formations/check-slugs', [FormationController::class, 'checkSlugDuplicates'])
        ->name('formations.check-slugs');
});

Route::post('/change-language', function (Illuminate\Http\Request $request) {
    $locale = $request->input('locale');

    if (in_array($locale, ['fr', 'en', 'es'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);

        return response()->json(['success' => true, 'locale' => $locale]);
    }

    return response()->json(['success' => false], 400);
})->name('change.language');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Routes authentifiées
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // ==============================================
    // DASHBOARD GÉNÉRAL - Redirection selon le rôle
    // ==============================================
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if (session('success')) {
            session()->keep(['success']);
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('chauffeur')) {
            return redirect()->route('chauffeur.dashboard');
        } elseif ($user->hasRole('formateur')) {
            return redirect()->route('formateur.dashboard');
        } else {
            return redirect()->route('client.dashboard');
        }
    })->name('dashboard');

    // ==============================================
    // PROFILE BREEZE
    // ==============================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Changement de langue
Route::post('/language/switch', [LanguageController::class, 'switch'])
    ->name('language.switch');

// Page de sélection de langue (optionnelle)
Route::get('/language', [LanguageController::class, 'index'])
    ->name('language.index');

/*
|--------------------------------------------------------------------------
| Routes des différents dashboards
|--------------------------------------------------------------------------
*/

// Inclure les routes admin
require __DIR__ . '/admin.php';

// Inclure les routes client
require __DIR__ . '/client.php';

// Inclure les routes chauffeur
require __DIR__ . '/chauffeur.php';

// Inclure les routes formateur
require __DIR__ . '/formateur.php';

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
