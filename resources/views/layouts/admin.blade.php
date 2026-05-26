<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') — {{ config('app.name', 'BookFlow') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-slate-50 font-sans antialiased" x-data="{ sidebarOpen: false }">

<div class="flex h-full">

    {{-- ===== SIDEBAR ADMIN ===== --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-slate-950 transition-transform duration-300 lg:static lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo --}}
        <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">
            <span class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white">
                <i class="fas fa-shield-halved text-sm"></i>
            </span>
            <div>
                <p class="text-sm font-black text-white">BookFlow</p>
                <p class="text-xs font-semibold text-amber-400">Administration</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-6">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard',       'icon' => 'fa-gauge-high',  'label' => 'Vue d\'ensemble'],
                    ['route' => 'admin.ebooks.index',    'icon' => 'fa-book',        'label' => 'E-books'],
                    ['route' => 'admin.categories.index','icon' => 'fa-tags',        'label' => 'Catégories'],
                    ['route' => 'admin.users.index',     'icon' => 'fa-users',       'label' => 'Utilisateurs'],
                ];
            @endphp
            @foreach($adminNav as $item)
                @php $active = request()->routeIs($item['route'].'*'); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold transition
                          {{ $active ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/30' : 'text-slate-400 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} w-4 text-center"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="my-3 border-t border-white/10"></div>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-400 transition hover:bg-white/10 hover:text-white">
                <i class="fas fa-arrow-left w-4 text-center"></i>
                Retour au site
            </a>
        </nav>

        {{-- User footer --}}
        <div class="border-t border-white/10 p-4">
            <div class="flex items-center gap-3">
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=f59e0b&background=1c1917' }}"
                     class="h-9 w-9 rounded-full ring-2 ring-amber-500/30" alt="{{ Auth::user()->name }}">
                <div class="min-w-0 flex-1">
                    <p class="truncate text-xs font-bold text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs font-semibold text-amber-400">Administrateur</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Déconnexion"
                            class="flex h-8 w-8 items-center justify-center rounded-xl text-slate-400 transition hover:bg-white/10 hover:text-rose-400">
                        <i class="fas fa-sign-out-alt text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Overlay mobile --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

    {{-- ===== MAIN AREA ===== --}}
    <div class="flex flex-1 flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="flex h-16 items-center justify-between border-b border-slate-200 bg-white px-6 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 lg:hidden">
                    <i class="fas fa-bars text-sm"></i>
                </button>
                <div>
                    <h1 class="text-base font-black text-slate-900">@yield('title', 'Administration')</h1>
                    <p class="text-xs text-slate-400">@yield('subtitle', 'Gestion de la plateforme')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="hidden items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 ring-1 ring-amber-200 sm:flex">
                    <i class="fas fa-shield-halved"></i> Admin
                </span>
                <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&color=f59e0b&background=fef3c7' }}"
                     class="h-9 w-9 rounded-full ring-2 ring-amber-100" alt="{{ Auth::user()->name }}">
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-6 mt-4">
            <div class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-sm font-semibold text-emerald-800">
                <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
            </div>
        </div>
        @endif
        @if(session('error'))
        <div class="mx-6 mt-4">
            <div class="flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-sm font-semibold text-rose-800">
                <i class="fas fa-exclamation-circle text-rose-500"></i> {{ session('error') }}
            </div>
        </div>
        @endif
        @if($errors->any())
        <div class="mx-6 mt-4">
            <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3.5 text-sm text-rose-800">
                <p class="font-bold"><i class="fas fa-triangle-exclamation mr-2"></i>Erreurs dans le formulaire :</p>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-xs">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
