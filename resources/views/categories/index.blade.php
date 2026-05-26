@extends('layouts.app')

@section('title', 'Catégories')

@section('content')
<section class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-10 text-center">
            <span class="section-eyebrow">Explorer</span>
            <h1 class="section-title">Toutes les catégories</h1>
            <p class="section-subtitle mx-auto">Parcourez nos catégories pour trouver le livre parfait</p>
        </div>

        {{-- Categories Grid --}}
        <div class="grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($categories as $category)
            @php
                $covers = $category->ebooks()->whereNotNull('cover_path')->take(3)->pluck('cover_path');
                $cnt = $covers->count();
            @endphp
            <a href="{{ route('public.categories.show', $category->slug) }}"
               class="group flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                {{-- Étagère de couvertures --}}
                <div class="relative flex h-44 items-end justify-center gap-2 overflow-hidden bg-gradient-to-b from-slate-100 to-slate-200 px-3 pb-0">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-transparent to-slate-200/80"></div>

                    @if($cnt === 0)
                        {{-- Aucune couverture --}}
                        <div class="relative flex h-36 w-24 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-200 to-violet-300 shadow-lg text-4xl">
                            {{ $category->icon ?? '📚' }}
                        </div>
                    @elseif($cnt === 1)
                        {{-- 1 couverture : centrée grande --}}
                        <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                             class="relative z-10 h-40 w-28 flex-shrink-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                    @elseif($cnt === 2)
                        {{-- 2 couvertures --}}
                        <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                             class="relative z-0 h-32 w-22 flex-shrink-0 -rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                        <img src="{{ Storage::url($covers[1]) }}" alt="cover"
                             class="relative z-10 h-40 w-28 flex-shrink-0 rotate-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                    @else
                        {{-- 3 couvertures : étagère --}}
                        <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                             class="relative z-0 h-32 w-20 flex-shrink-0 -rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                        <img src="{{ Storage::url($covers[1]) }}" alt="cover"
                             class="relative z-10 h-40 w-28 flex-shrink-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                        <img src="{{ Storage::url($covers[2]) }}" alt="cover"
                             class="relative z-0 h-32 w-20 flex-shrink-0 rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                    @endif
                </div>

                {{-- Infos catégorie --}}
                <div class="flex items-center justify-between border-t border-slate-100 bg-white px-4 py-3">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition">
                            {{ $category->icon ?? '' }} {{ $category->name }}
                        </h3>
                        <p class="text-xs text-slate-400">{{ $category->ebooks_count }} livre{{ $category->ebooks_count != 1 ? 's' : '' }}</p>
                    </div>
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-16 text-center">
                <i class="fas fa-folder-open mb-4 text-5xl text-slate-200"></i>
                <h3 class="text-lg font-bold text-slate-600">Aucune catégorie disponible</h3>
            </div>
            @endforelse
        </div>

        {{-- Back to catalogue --}}
        <div class="mt-12 text-center">
            <a href="{{ route('public.ebooks.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Retour au catalogue
            </a>
        </div>
    </div>
</section>
@endsection
