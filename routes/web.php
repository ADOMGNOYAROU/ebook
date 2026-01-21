<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\EbookController as AdminEbookController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\PublicEbookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

// Route de test pour le composant EbookCard
Route::get('/test-component', TestController::class)->name('test.component');

/*
|--------------------------------------------------------------------------
| ROUTES PUBLIQUES
| Accessibles à tous les visiteurs
|--------------------------------------------------------------------------
*/
Route::prefix('/')->name('public.')->group(function () {
    // Page d'accueil
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Catalogue des ebooks
    Route::get('/ebooks', [PublicEbookController::class, 'index'])->name('ebooks.index');
    
    // Détails d'un ebook
    Route::get('/ebooks/{ebook:slug}', [PublicEbookController::class, 'show'])->name('ebooks.show');
    
    // Liste des catégories
    Route::get('/categories', [PublicEbookController::class, 'categoriesIndex'])->name('categories.index');
    
    // Ebooks par catégorie
    Route::get('/categories/{category:slug}', [PublicEbookController::class, 'category'])->name('categories.show');
    
    // Télécharger un ebook (accessible à tous)
    Route::post('/ebooks/{ebook}/download', [PublicEbookController::class, 'download'])
        ->name('ebooks.download');
});

/*
|--------------------------------------------------------------------------
| ROUTES AUTHENTIFIÉES
| Accessibles uniquement aux utilisateurs connectés et vérifiés
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Tableau de bord utilisateur
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profil utilisateur
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{ebook}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    
    // Téléchargements
    Route::get('/downloads/history', [DownloadController::class, 'index'])->name('downloads.history');
    
    // Paiements
    Route::get('/checkout/{ebook}', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/process-payment/{ebook}', [PaymentController::class, 'processPayment'])->name('process.payment');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
    
    // Webhook Stripe (doit être en dehors du middleware CSRF)
    Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])
        ->name('stripe.webhook')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMINISTRATEUR
| Accessibles uniquement aux administrateurs
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {
        // Dashboard administrateur
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Statistiques
        Route::get('/stats', [AdminDashboardController::class, 'stats'])->name('stats');
        
        // Gestion des catégories
        Route::resource('categories', AdminCategoryController::class)->except(['show']);
        
        // Gestion des ebooks
        Route::resource('ebooks', AdminEbookController::class);
        
        // Gestion des utilisateurs
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    });

// Routes d'authentification (Breeze)
require __DIR__.'/auth.php';
