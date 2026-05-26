@extends('layouts.app')

@section('content')
<section class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-10">
            <h1 class="section-title">Catalogue d'e-books</h1>
            <p class="section-subtitle">Explorez notre collection complète de livres numériques</p>
        </div>

        {{-- Filtres par genre / catégorie avec images --}}
        <div class="mb-10">
            <h2 class="mb-4 text-sm font-black uppercase tracking-wider text-slate-400">Parcourir par catégorie</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                {{-- Tous les genres --}}
                <a href="{{ route('public.ebooks.index') }}"
                   class="group relative flex h-32 flex-col items-center justify-center overflow-hidden rounded-2xl border-2 transition hover:-translate-y-1 hover:shadow-lg {{ !request('category') ? 'border-indigo-500 bg-indigo-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-indigo-300' }}">
                    <span class="text-sm font-bold">Tous</span>
                </a>

                @php
                    $catGradients = [
                        'roman'                   => 'from-amber-500 to-orange-600',
                        'science-fiction'         => 'from-blue-500 to-cyan-600',
                        'fantasy'                 => 'from-violet-500 to-purple-700',
                        'policier'                => 'from-slate-600 to-gray-800',
                        'biographie'              => 'from-emerald-500 to-teal-600',
                        'developpement-personnel' => 'from-rose-500 to-pink-600',
                        'histoire'                => 'from-yellow-500 to-amber-700',
                        'science'                 => 'from-indigo-500 to-blue-700',
                    ];
                    $catIcons = [
                        'roman'                   => 'fa-book-open',
                        'science-fiction'         => 'fa-rocket',
                        'fantasy'                 => 'fa-hat-wizard',
                        'policier'                => 'fa-magnifying-glass',
                        'biographie'              => 'fa-user-pen',
                        'developpement-personnel' => 'fa-brain',
                        'histoire'                => 'fa-landmark',
                        'science'                 => 'fa-flask',
                    ];
                @endphp
                @foreach($categories as $category)
                @php
                    $covers = $category->ebooks()->whereNotNull('cover_path')->where('cover_path', '!=', '')->take(3)->pluck('cover_path');
                    $cnt = $covers->count();
                    $isActive = request('category') === $category->slug;
                    $grad = $catGradients[$category->slug] ?? 'from-indigo-500 to-violet-600';
                @endphp
                <a href="{{ route('public.ebooks.index', array_merge($filters, ['category' => $category->slug])) }}"
                   class="group flex flex-col overflow-hidden rounded-3xl border-2 transition hover:-translate-y-1 hover:shadow-xl {{ $isActive ? 'border-indigo-500 ring-2 ring-indigo-500/30' : 'border-slate-200 hover:border-indigo-300' }}">

                    {{-- Étagère de couvertures --}}
                    <div class="relative flex h-40 items-end justify-center gap-2 overflow-hidden bg-gradient-to-b from-slate-100 to-slate-200 px-3 pb-0">
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-transparent to-slate-200/80"></div>

                        @if($cnt === 0)
                            <div class="relative flex h-32 w-24 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br {{ $grad }} shadow-lg">
                            </div>
                        @elseif($cnt === 1)
                            <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                                 class="relative z-10 h-36 w-24 flex-shrink-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                        @elseif($cnt === 2)
                            <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                                 class="relative z-0 h-28 w-20 flex-shrink-0 -rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                            <img src="{{ Storage::url($covers[1]) }}" alt="cover"
                                 class="relative z-10 h-36 w-24 flex-shrink-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                        @else
                            <img src="{{ Storage::url($covers[0]) }}" alt="cover"
                                 class="relative z-0 h-28 w-20 flex-shrink-0 -rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                            <img src="{{ Storage::url($covers[1]) }}" alt="cover"
                                 class="relative z-10 h-36 w-24 flex-shrink-0 rounded-xl object-cover shadow-xl transition duration-300 group-hover:scale-105">
                            <img src="{{ Storage::url($covers[2]) }}" alt="cover"
                                 class="relative z-0 h-28 w-20 flex-shrink-0 rotate-6 rounded-xl object-cover shadow-lg transition duration-300 group-hover:scale-105">
                        @endif
                    </div>

                    {{-- Infos catégorie --}}
                    <div class="flex items-center justify-between border-t border-slate-100 bg-white px-4 py-3">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition">
                                {{ $category->name }}
                            </h3>
                            <p class="text-xs text-slate-400">{{ $category->ebooks_count }} livre{{ $category->ebooks_count != 1 ? 's' : '' }}</p>
                        </div>
                        <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                            <i class="fas fa-arrow-right text-xs"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Filtres secondaires (langue, type, tri) --}}
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap items-center gap-3">
                {{-- Recherche --}}
                <form action="{{ route('public.ebooks.index') }}" method="GET" class="flex items-center gap-2">
                    @foreach($filters as $key => $value)
                        @if($key !== 'search')<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif
                    @endforeach
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                           class="w-48 rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                    <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                {{-- Filtre gratuit/payant --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('public.ebooks.index', array_merge($filters, ['type' => null])) }}" 
                       class="pill-sm {{ !request('type') ? 'pill-sm-active' : '' }}">Tous</a>
                    <a href="{{ route('public.ebooks.index', array_merge($filters, ['type' => 'free'])) }}" 
                       class="pill-sm {{ request('type') === 'free' ? 'pill-sm-active' : '' }}">Gratuits</a>
                    <a href="{{ route('public.ebooks.index', array_merge($filters, ['type' => 'paid'])) }}" 
                       class="pill-sm {{ request('type') === 'paid' ? 'pill-sm-active' : '' }}">Payants</a>
                </div>
            </div>

            {{-- Tri --}}
            <select onchange="window.location.href=this.value" class="rounded-xl border border-slate-200 px-4 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                @foreach(['latest' => 'Plus récents', 'popular' => 'Plus populaires', 'title' => 'A-Z'] as $key => $label)
                    <option value="{{ route('public.ebooks.index', array_merge($filters, ['sort' => $key])) }}" {{ request('sort') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Grille d'ebooks --}}
        @if($ebooks->count() > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($ebooks as $ebook)
                    <a href="{{ route('public.ebooks.show', $ebook) }}" class="ebook-card group">
                        {{-- Cover --}}
                        <div class="relative mb-4 overflow-hidden rounded-2xl bg-slate-100">
                            @if($ebook->cover_path)
                                <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}"
                                     class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-56 w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                </div>
                            @endif
                            {{-- Badge gratuit --}}
                            @if($ebook->is_free)
                                <span class="absolute top-3 left-3 rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-bold text-white shadow-lg">
                                    Gratuit
                                </span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div>
                            <h3 class="text-base font-black text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition">
                                {{ $ebook->title }}
                            </h3>
                            <p class="mt-1 text-sm font-semibold text-slate-400">{{ $ebook->author }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    {{ $ebook->category->name }}
                                </span>
                                <span class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                                    <i class="fas fa-download"></i> {{ number_format($ebook->downloads_count) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination moderne --}}
            <div class="mt-12 flex justify-center">
                {{ $ebooks->onEachSide(1)->appends($filters)->links('pagination::tailwind') }}
            </div>
        @else
            <div class="rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
                <i class="fas fa-search mb-4 text-4xl text-slate-300"></i>
                <h3 class="text-lg font-bold text-slate-800">Aucun e-book trouvé</h3>
                <p class="mt-2 text-slate-400">Essayez de modifier vos filtres ou votre recherche</p>
                <a href="{{ route('public.ebooks.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-undo"></i>Réinitialiser les filtres
                </a>
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.pill-filter {
    @apply inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700;
}
.pill-active {
    @apply border-indigo-500 bg-indigo-600 text-white hover:bg-indigo-700 hover:text-white hover:border-indigo-600;
}
.pill-sm {
    @apply rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700;
}
.pill-sm-active {
    @apply border-indigo-500 bg-indigo-600 text-white;
}
.ebook-card {
    @apply block rounded-2xl border border-slate-200 bg-white p-4 transition-shadow hover:shadow-xl hover:shadow-indigo-500/10;
}
</style>
@endpush
@endsection
