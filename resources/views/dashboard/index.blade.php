@extends('layouts.dashboard')

@section('title', 'Tableau de bord')
@section('subtitle', 'Bienvenue, {{ Auth::user()->name }} 👋')

@section('content')

{{-- ===== WELCOME BANNER ===== --}}
<div class="mb-6 overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 px-8 py-8 text-white shadow-xl shadow-indigo-500/20">
    <div class="flex items-center gap-4">
        <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm text-3xl ring-2 ring-white/30">
            👋
        </div>
        <div>
            <h1 class="text-2xl font-black">Bienvenue, {{ Auth::user()->name }} !</h1>
            <p class="mt-1 text-indigo-100">Heureux de vous revoir sur votre espace personnel</p>
        </div>
    </div>
    @if(Auth::user()->isPremium())
    <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold backdrop-blur-sm">
        <i class="fas fa-crown text-amber-300"></i> Compte Premium actif
    </div>
    @elseif(Auth::user()->isFree())
    <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold backdrop-blur-sm">
        <i class="fas fa-gift"></i> {{ Auth::user()->downloads_remaining }} téléchargements gratuits restants
        <a href="{{ route('user.subscription.index') }}" class="ml-2 underline hover:text-white">Passer Premium →</a>
    </div>
    @endif
</div>

{{-- ===== KPI CARDS ===== --}}
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $kpis = [
            ['label' => 'Téléchargements', 'value' => $userDownloads->count(), 'icon' => 'fa-download',  'color' => 'indigo',   'href' => route('downloads.history')],
            ['label' => 'Favoris',         'value' => $userFavorites->count(), 'icon' => 'fa-heart',     'color' => 'rose',     'href' => route('favorites.index')],
            ['label' => 'Avis laissés',    'value' => $userReviews->count(),   'icon' => 'fa-star',      'color' => 'amber',    'href' => route('dashboard')],
            ['label' => 'Notifications',   'value' => $unreadNotifications->count(), 'icon' => 'fa-bell','color' => 'violet',   'href' => route('dashboard')],
        ];
        $colorMap = [
            'indigo' => ['bg' => 'bg-indigo-50', 'icon' => 'text-indigo-600', 'ring' => 'ring-indigo-100'],
            'rose'   => ['bg' => 'bg-rose-50',   'icon' => 'text-rose-600',   'ring' => 'ring-rose-100'],
            'amber'  => ['bg' => 'bg-amber-50',  'icon' => 'text-amber-600',  'ring' => 'ring-amber-100'],
            'violet' => ['bg' => 'bg-violet-50', 'icon' => 'text-violet-600', 'ring' => 'ring-violet-100'],
        ];
    @endphp
    @foreach($kpis as $kpi)
    @php $c = $colorMap[$kpi['color']]; @endphp
    <a href="{{ $kpi['href'] }}"
       class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl {{ $c['bg'] }} ring-4 {{ $c['ring'] }}">
            <i class="fas {{ $kpi['icon'] }} {{ $c['icon'] }}"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-900">{{ $kpi['value'] }}</p>
            <p class="text-xs font-semibold text-slate-400">{{ $kpi['label'] }}</p>
        </div>
    </a>
    @endforeach
</div>

{{-- ===== MAIN GRID ===== --}}
<div class="mt-6 grid gap-6 lg:grid-cols-3">

    {{-- Derniers téléchargements --}}
    <div class="lg:col-span-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h2 class="text-sm font-black text-slate-900">Derniers téléchargements</h2>
                <a href="{{ route('downloads.history') }}"
                   class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Voir tout →</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse($userDownloads->take(6) as $download)
                <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <i class="fas fa-file-pdf text-sm"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-bold text-slate-800">{{ $download->ebook->title }}</p>
                        <p class="text-xs text-slate-400">{{ $download->ebook->author }}</p>
                    </div>
                    <span class="flex-shrink-0 text-xs text-slate-400">{{ $download->created_at->diffForHumans() }}</span>
                </li>
                @empty
                <li class="px-6 py-10 text-center">
                    <i class="fas fa-inbox mb-3 text-3xl text-slate-200"></i>
                    <p class="text-sm font-semibold text-slate-400">Aucun téléchargement pour l'instant</p>
                    <a href="{{ route('public.ebooks.index') }}" class="mt-2 inline-block text-xs font-bold text-indigo-600 hover:underline">
                        Parcourir le catalogue
                    </a>
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Suggestions --}}
    <div class="space-y-4">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-sm font-black text-slate-900">Suggestions pour vous</h2>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse($recommendedEbooks as $suggestion)
                <li class="flex items-center gap-3 px-5 py-4 hover:bg-slate-50 transition">
                    @if($suggestion->cover_path)
                        <img src="{{ Storage::url($suggestion->cover_path) }}" alt="{{ $suggestion->title }}"
                             class="h-12 w-9 flex-shrink-0 rounded-lg object-cover shadow">
                    @else
                        <div class="flex h-12 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-400">
                            <i class="fas fa-book text-xs"></i>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-xs font-bold text-slate-800">{{ $suggestion->title }}</p>
                        <p class="text-xs text-slate-400">{{ $suggestion->author }}</p>
                        <a href="{{ route('public.ebooks.show', $suggestion) }}"
                           class="mt-0.5 inline-block text-xs font-bold text-indigo-600 hover:underline">Voir →</a>
                    </div>
                </li>
                @empty
                <li class="px-6 py-8 text-center">
                    <p class="text-xs font-semibold text-slate-400">Aucune suggestion disponible</p>
                </li>
                @endforelse
            </ul>
        </div>

        {{-- Quick links --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-4">
                <h2 class="text-sm font-black text-slate-900">Accès rapide</h2>
            </div>
            <div class="grid grid-cols-2 gap-3 p-4">
                <a href="{{ route('public.ebooks.index') }}"
                   class="flex flex-col items-center justify-center gap-2 rounded-2xl bg-indigo-50 p-4 text-indigo-600 transition hover:bg-indigo-100">
                    <i class="fas fa-compass text-xl"></i>
                    <span class="text-xs font-bold">Catalogue</span>
                </a>
                <a href="{{ route('favorites.index') }}"
                   class="flex flex-col items-center justify-center gap-2 rounded-2xl bg-rose-50 p-4 text-rose-600 transition hover:bg-rose-100">
                    <i class="fas fa-heart text-xl"></i>
                    <span class="text-xs font-bold">Favoris</span>
                </a>
                <a href="{{ route('profile.edit') }}"
                   class="flex flex-col items-center justify-center gap-2 rounded-2xl bg-violet-50 p-4 text-violet-600 transition hover:bg-violet-100">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="text-xs font-bold">Profil</span>
                </a>
                <a href="{{ route('downloads.history') }}"
                   class="flex flex-col items-center justify-center gap-2 rounded-2xl bg-emerald-50 p-4 text-emerald-600 transition hover:bg-emerald-100">
                    <i class="fas fa-download text-xl"></i>
                    <span class="text-xs font-bold">Télécharg.</span>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
