<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'E-Book Platform') }} - Administration</title>
    <meta name="description" content="Panneau d'administration de la plateforme E-Book">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    @stack('styles')
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            min-height: 100%;
            width: 100%;
            overflow-x: hidden;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            padding-top: 0 !important;
            background-color: #f9fafb;
        }
        
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: 40;
            overflow-y: auto;
        }
        
        .admin-content {
            margin-left: 250px;
            min-height: 100vh;
            padding-top: 4rem;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="p-4">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-open text-indigo-600"></i>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg">E-BookHub</h1>
                        <p class="text-indigo-200 text-xs">Administration</p>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Tableau de bord</span>
                    </a>
                    
                    <a href="{{ route('admin.ebooks.index') }}" class="flex items-center space-x-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.ebooks.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-book w-5"></i>
                        <span>E-books</span>
                    </a>
                    
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-tags w-5"></i>
                        <span>Catégories</span>
                    </a>
                    
                    <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-white bg-opacity-20' : '' }}">
                        <i class="fas fa-users w-5"></i>
                        <span>Utilisateurs</span>
                    </a>
                    
                    <div class="border-t border-white border-opacity-20 my-4"></div>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-white hover:bg-white hover:bg-opacity-20 rounded-lg px-3 py-2 transition-colors">
                        <i class="fas fa-arrow-left w-5"></i>
                        <span>Retour au site</span>
                    </a>
                </nav>
            </div>
            
            <!-- User Info -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white border-opacity-20">
                <div class="flex items-center space-x-3">
                    <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                    <div class="flex-1">
                        <p class="text-white text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-indigo-200 text-xs">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full text-center text-white text-sm hover:bg-white hover:bg-opacity-20 rounded px-3 py-2 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content flex-1 overflow-y-auto">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 fixed top-0 right-0 left-0 z-30" style="left: 250px;">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleSidebar()">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <h2 class="ml-4 text-xl font-semibold text-gray-800">
                                @yield('title', 'Administration')
                            </h2>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <!-- Notifications -->
                            <button class="relative text-gray-500 hover:text-gray-700 focus:outline-none">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-white text-xs flex items-center justify-center">3</span>
                            </button>
                            
                            <!-- User Menu -->
                            <div class="relative">
                                <button class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                                    <img class="w-8 h-8 rounded-full" src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="{{ Auth::user()->name }}">
                                    <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-check-circle mt-0.5 mr-2"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mt-0.5 mr-2"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                            <div>
                                <p class="font-medium">Il y a des erreurs dans le formulaire :</p>
                                <ul class="mt-1 list-disc list-inside text-sm">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.admin-sidebar');
            sidebar.classList.toggle('open');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.admin-sidebar');
            const toggleButton = event.target.closest('button[onclick="toggleSidebar()"]');
            
            if (window.innerWidth < 768 && !sidebar.contains(event.target) && !toggleButton) {
                sidebar.classList.remove('open');
            }
        });
    </script>
    @endstack
</body>
</html>
