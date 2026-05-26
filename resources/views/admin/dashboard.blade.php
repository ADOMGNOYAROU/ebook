@extends('layouts.admin')

@section('title', 'Vue d\'ensemble')
@section('subtitle', 'Bienvenue dans votre espace administration')

@section('content')

{{-- KPI CARDS --}}
<div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
    @php
        $kpis = [
            ['label'=>'E-books',         'value'=> number_format($stats['totalEbooks']),     'icon'=>'fa-book',         'color'=>'indigo'],
            ['label'=>'Utilisateurs',    'value'=> number_format($stats['totalUsers']),      'icon'=>'fa-users',        'color'=>'emerald'],
            ['label'=>'Téléchargements', 'value'=> number_format($stats['totalDownloads']),  'icon'=>'fa-download',     'color'=>'amber'],
            ['label'=>'Catégories',      'value'=> number_format($stats['totalCategories']), 'icon'=>'fa-tags',         'color'=>'violet'],
        ];
        $clr = [
            'indigo'  => ['bg'=>'bg-indigo-50',  'ic'=>'text-indigo-600',  'ring'=>'ring-indigo-100'],
            'emerald' => ['bg'=>'bg-emerald-50', 'ic'=>'text-emerald-600', 'ring'=>'ring-emerald-100'],
            'amber'   => ['bg'=>'bg-amber-50',   'ic'=>'text-amber-600',   'ring'=>'ring-amber-100'],
            'violet'  => ['bg'=>'bg-violet-50',  'ic'=>'text-violet-600',  'ring'=>'ring-violet-100'],
        ];
    @endphp
    @foreach($kpis as $k)
    @php $c = $clr[$k['color']]; @endphp
    <div class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl {{ $c['bg'] }} ring-4 {{ $c['ring'] }}">
            <i class="fas {{ $k['icon'] }} {{ $c['ic'] }}"></i>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-900">{{ $k['value'] }}</p>
            <p class="text-xs font-semibold text-slate-400">{{ $k['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ACTIONS RAPIDES --}}
<div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
    <a href="{{ route('admin.ebooks.create') }}"
       class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
            <i class="fas fa-plus text-sm"></i>
        </span>
        <span class="text-sm font-bold text-slate-700">Ajouter ebook</span>
    </a>
    <a href="{{ route('admin.categories.index') }}"
       class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
            <i class="fas fa-tags text-sm"></i>
        </span>
        <span class="text-sm font-bold text-slate-700">Catégories</span>
    </a>
    <a href="{{ route('admin.users.index') }}"
       class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-md">
        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
            <i class="fas fa-users text-sm"></i>
        </span>
        <span class="text-sm font-bold text-slate-700">Utilisateurs</span>
    </a>
    <a href="{{ route('admin.stats') }}"
       class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3.5 shadow-sm transition hover:-translate-y-0.5 hover:border-violet-200 hover:shadow-md">
        <span class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-violet-50 text-violet-600">
            <i class="fas fa-chart-bar text-sm"></i>
        </span>
        <span class="text-sm font-bold text-slate-700">Statistiques</span>
    </a>
</div>

{{-- LISTES --}}
<div class="mt-6 grid gap-6 lg:grid-cols-2">

    {{-- Derniers ebooks --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 class="text-sm font-black text-slate-900">Derniers e-books ajoutés</h2>
            <a href="{{ route('admin.ebooks.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">Voir tout →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse($stats['recentEbooks'] as $ebook)
            <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-500">
                    <i class="fas fa-book text-sm"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">{{ $ebook->title }}</p>
                    <p class="text-xs text-slate-400">{{ $ebook->author }} · {{ $ebook->created_at->format('d/m/Y') }}</p>
                </div>
                <a href="{{ route('admin.ebooks.edit', $ebook) }}"
                   class="flex-shrink-0 rounded-xl bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-700 transition">
                    Éditer
                </a>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-sm text-slate-400">Aucun ebook</li>
            @endforelse
        </ul>
    </div>

    {{-- Derniers utilisateurs --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
            <h2 class="text-sm font-black text-slate-900">Derniers inscrits</h2>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700">Voir tout →</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse($stats['recentUsers'] as $user)
            <li class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition">
                <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=f59e0b&background=fef3c7' }}"
                     class="h-9 w-9 flex-shrink-0 rounded-full ring-2 ring-slate-100" alt="{{ $user->name }}">
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-bold text-slate-800">{{ $user->name }}</p>
                    <p class="truncate text-xs text-slate-400">{{ $user->email }}</p>
                </div>
                <span class="flex-shrink-0 text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</span>
            </li>
            @empty
            <li class="px-6 py-8 text-center text-sm text-slate-400">Aucun utilisateur</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection
