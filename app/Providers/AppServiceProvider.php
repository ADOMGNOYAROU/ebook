<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrer le composant ebook-card
        \Illuminate\Support\Facades\Blade::component('ebooks.ebook-card', \App\View\Components\EbookCard::class);
    }
}
