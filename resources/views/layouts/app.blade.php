<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Book Platform') }}</title>
    <meta name="description" content="Plateforme SaaS moderne pour lire, vendre et gérer des e-books en ligne">
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-950 font-sans text-slate-900 antialiased">
    <div class="min-h-screen bg-[radial-gradient(circle_at_top_left,_rgba(99,102,241,0.18),_transparent_32rem),linear-gradient(180deg,#f8fafc_0%,#eef2ff_38%,#ffffff_100%)]">
        @if(!in_array(Route::currentRouteName(), ['login', 'register', 'password.request', 'password.reset']))
            @include('layouts.navigation')
        @endif

        <main class="min-h-screen pt-24">
            @if(session('status'))
                <div class="mx-auto mb-6 max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-800 shadow-sm">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mx-auto mb-6 max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-800 shadow-sm">
                        <p class="font-semibold"><i class="fas fa-triangle-exclamation mr-2"></i>Il y a {{ $errors->count() }} erreur(s) dans votre formulaire</p>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
