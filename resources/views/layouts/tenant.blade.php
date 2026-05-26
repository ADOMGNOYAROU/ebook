<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $tenant->name ?? 'E-Book Platform' }} - @yield('title', 'Tableau de bord')</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/tenant.css'])
    
    <!-- Custom tenant colors -->
    <style>
        :root {
            --primary-color: {{ $tenant->primary_color ?? '#6366f1' }};
            --secondary-color: {{ $tenant->secondary_color ?? '#8b5cf6' }};
            --primary-hover: {{ $tenant->primary_color ?? '#6366f1' }}dd;
        }
    </style>
</head>
<body class="bg-gray-50 font-inter">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-white border-r border-gray-200 w-64 flex-shrink-0">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    @if($tenant->logo)
                        <img src="{{ Storage::url($tenant->logo) }}" alt="{{ $tenant->name }}" class="h-10 w-10 rounded-lg">
                    @else
                        <div class="h-10 w-10 rounded-lg flex items-center justify-center" style="background-color: var(--primary-color);">
                            <span class="text-white font-bold text-lg">{{ substr($tenant->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900">{{ $tenant->name }}</h1>
                        <p class="text-xs text-gray-500">{{ $tenant->currentPlan()?->name ?? 'Aucun plan' }}</p>
                    </div>
                </div>
            </div>
            
            <nav class="p-4">
                <div class="space-y-1">
                    <a href="{{ route('tenant.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-home mr-3 text-gray-400"></i>
                        Tableau de bord
                    </a>
                    
                    <a href="{{ route('tenant.ebooks.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.ebooks.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-book mr-3 text-gray-400"></i>
                        Mes E-books
                    </a>
                    
                    <a href="{{ route('tenant.categories.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.categories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-folder mr-3 text-gray-400"></i>
                        Catégories
                    </a>
                    
                    <a href="{{ route('tenant.analytics') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.analytics') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-chart-bar mr-3 text-gray-400"></i>
                        Analytics
                    </a>
                    
                    @if($tenant->hasFeature('api_access'))
                    <a href="{{ route('tenant.api') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.api') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-code mr-3 text-gray-400"></i>
                        API
                    </a>
                    @endif
                    
                    <a href="{{ route('tenant.settings') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-cog mr-3 text-gray-400"></i>
                        Paramètres
                    </a>
                </div>
                
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('subscriptions.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <i class="fas fa-credit-card mr-3 text-gray-400"></i>
                        Abonnement
                        @if($tenant->subscription)
                            <span class="ml-auto text-xs px-2 py-1 rounded-full {{ $tenant->subscription->isActive() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $tenant->subscription->formatted_status }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('tenant.users.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('tenant.users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-users mr-3 text-gray-400"></i>
                        Utilisateurs
                        <span class="ml-auto text-xs text-gray-500">{{ $tenant->users()->count() }}/{{ $tenant->currentPlan()?->max_users_display ?? '∞' }}</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white border-b border-gray-200">
                <div class="flex justify-between items-center px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <h1 class="text-xl font-semibold text-gray-900">@yield('title', 'Tableau de bord')</h1>
                        
                        <!-- Usage indicators -->
                        <div class="flex items-center space-x-3 text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-book mr-1"></i>
                                {{ $tenant->ebooks()->count() }}/{{ $tenant->currentPlan()?->max_ebooks_display ?? '∞' }}
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-database mr-1"></i>
                                {{ $tenant->getStorageUsed() }}Mo/{{ $tenant->currentPlan()?->storage_display ?? '∞' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-1 right-1 h-2 w-2 bg-red-500 rounded-full"></span>
                        </button>
                        
                        <!-- User menu -->
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                            <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" 
                                 alt="{{ Auth::user()->name }}" 
                                 class="h-8 w-8 rounded-full">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-800">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-800">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg text-blue-800">
                        {{ session('info') }}
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>
</html>
