<?php

use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Tenant\EbookController;
use App\Http\Controllers\Tenant\CategoryController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\AnalyticsController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\Tenant\UserController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ROUTES SAAS - MULTI-TENANTS
|--------------------------------------------------------------------------
*/

// Landing page marketing
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| ROUTES TENANTS (SOUS-DOMAINES)
|--------------------------------------------------------------------------
*/
Route::middleware(['web', 'identif.tenant'])->prefix('/')->name('tenant.')->group(function () {
    
    // Dashboard tenant
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Gestion des ebooks
    Route::prefix('ebooks')->name('ebooks.')->group(function () {
        Route::get('/', [EbookController::class, 'index'])->name('index');
        Route::get('/create', [EbookController::class, 'create'])->name('create');
        Route::post('/', [EbookController::class, 'store'])->name('store');
        Route::get('/{ebook}', [EbookController::class, 'show'])->name('show');
        Route::get('/{ebook}/edit', [EbookController::class, 'edit'])->name('edit');
        Route::put('/{ebook}', [EbookController::class, 'update'])->name('update');
        Route::delete('/{ebook}', [EbookController::class, 'destroy'])->name('destroy');
        Route::post('/{ebook}/upload', [EbookController::class, 'upload'])->name('upload');
        Route::get('/{ebook}/download', [EbookController::class, 'download'])->name('download');
    });
    
    // Gestion des catégories
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');
    
    // API Documentation
    Route::get('/api', [AnalyticsController::class, 'apiDocumentation'])->name('api');
    
    // Paramètres du tenant
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::put('/', [SettingsController::class, 'update'])->name('update');
        Route::post('/logo', [SettingsController::class, 'uploadLogo'])->name('upload.logo');
        Route::delete('/logo', [SettingsController::class, 'deleteLogo'])->name('delete.logo');
    });
    
    // Gestion des utilisateurs du tenant
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/role', [UserController::class, 'updateRole'])->name('role');
    });
});

/*
|--------------------------------------------------------------------------
| ROUTES ABONNEMENTS (GLOBALES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->prefix('/billing')->name('billing.')->group(function () {
    // Gestion des abonnements
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/checkout/{plan}', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/{plan}', [SubscriptionController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/success', [SubscriptionController::class, 'success'])->name('success');
    Route::get('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    Route::post('/cancel', [SubscriptionController::class, 'processCancel'])->name('cancel.process');
    Route::post('/resume', [SubscriptionController::class, 'resume'])->name('resume');
    Route::post('/swap', [SubscriptionController::class, 'swap'])->name('swap');
    
    // Webhook Stripe
    Route::post('/stripe/webhook', [SubscriptionController::class, 'webhook'])
        ->name('stripe.webhook')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});

/*
|--------------------------------------------------------------------------
| ROUTES ADMINISTRATION SAAS (SUPER-ADMIN)
|--------------------------------------------------------------------------
*/
Route::prefix('saas-admin')
    ->middleware(['auth', 'super.admin'])
    ->name('saas-admin.')
    ->group(function () {
        // Dashboard SaaS
        Route::get('/', [\App\Http\Controllers\Saas\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des tenants
        Route::prefix('tenants')->name('tenants.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'store'])->name('store');
            Route::get('/{tenant}', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'show'])->name('show');
            Route::get('/{tenant}/edit', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'edit'])->name('edit');
            Route::put('/{tenant}', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'update'])->name('update');
            Route::delete('/{tenant}', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'destroy'])->name('destroy');
            Route::post('/{tenant}/suspend', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'suspend'])->name('suspend');
            Route::post('/{tenant}/activate', [\App\Http\Controllers\Saas\Admin\TenantController::class, 'activate'])->name('activate');
        });
        
        // Gestion des plans
        Route::prefix('plans')->name('plans.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'store'])->name('store');
            Route::get('/{plan}/edit', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'edit'])->name('edit');
            Route::put('/{plan}', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'update'])->name('update');
            Route::delete('/{plan}', [\App\Http\Controllers\Saas\Admin\PlanController::class, 'destroy'])->name('destroy');
        });
        
        // Statistiques SaaS
        Route::get('/stats', [\App\Http\Controllers\Saas\Admin\StatsController::class, 'index'])->name('stats');
        Route::get('/revenue', [\App\Http\Controllers\Saas\Admin\StatsController::class, 'revenue'])->name('revenue');
        Route::get('/churn', [\App\Http\Controllers\Saas\Admin\StatsController::class, 'churn'])->name('churn');
        
        // Gestion globale des utilisateurs
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Saas\Admin\UserController::class, 'index'])->name('index');
            Route::get('/{user}', [\App\Http\Controllers\Saas\Admin\UserController::class, 'show'])->name('show');
            Route::post('/{user}/impersonate', [\App\Http\Controllers\Saas\Admin\UserController::class, 'impersonate'])->name('impersonate');
        });
    });
