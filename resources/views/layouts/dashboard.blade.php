<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Book Platform') }} - Tableau de bord</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/dashboard.css'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-indigo-700 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Mon Compte</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 text-white hover:bg-indigo-800 {{ request()->routeIs('dashboard') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Tableau de bord
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center px-6 py-3 text-white hover:bg-indigo-800 {{ request()->routeIs('profile.*') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-user mr-3"></i>
                    Mon profil
                </a>
                <a href="{{ route('favorites.index') }}" class="flex items-center px-6 py-3 text-white hover:bg-indigo-800 {{ request()->routeIs('favorites.*') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-heart mr-3"></i>
                    Mes favoris
                </a>
                <a href="{{ route('downloads.history') }}" class="flex items-center px-6 py-3 text-white hover:bg-indigo-800 {{ request()->routeIs('downloads.*') ? 'bg-indigo-800' : '' }}">
                    <i class="fas fa-download mr-3"></i>
                    Mes téléchargements
                </a>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-6 py-3 text-white hover:bg-indigo-800">
                        <i class="fas fa-sign-out-alt mr-3"></i>
                        Déconnexion
                    </button>
                </form>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center px-6 py-4">
                    <h1 class="text-xl font-semibold text-gray-800">@yield('title', 'Tableau de bord')</h1>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">Bonjour, {{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                             alt="{{ Auth::user()->name }}" 
                             class="h-10 w-10 rounded-full">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
