@extends('layouts.dashboard')

@section('title', 'Catalogue')
@section('subtitle', 'Explorez notre collection complète')

@section('content')
@php
    // Récupérer les mêmes données que la vue publique
    $categories = \App\Models\Category::withCount('ebooks')->get();
    $ebooks = \App\Models\Ebook::with('category')
        ->when(request('category'), function($q) {
            $q->whereHas('category', fn($cat) => $cat->where('slug', request('category')));
        })
        ->when(request('type'), function($q) {
            if(request('type') === 'free') $q->where('is_free', true);
            elseif(request('type') === 'paid') $q->where('is_free', false);
        })
        ->when(request('search'), function($q) {
            $q->where('title', 'like', '%'.request('search').'%')
              ->orWhere('author', 'like', '%'.request('search').'%');
        })
        ->when(request('sort'), function($q) {
            if(request('sort') === 'popular') $q->orderBy('downloads_count', 'desc');
            elseif(request('sort') === 'latest') $q->orderBy('created_at', 'desc');
            elseif(request('sort') === 'rating') $q->orderBy('reviews_avg_rating', 'desc');
            else $q->orderBy('created_at', 'desc');
        })
        ->paginate(12);
    $ebooks->appends(request()->query());
@endphp

<section class="px-4 py-8 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="section-title">Catalogue d'e-books</h1>
            <p class="section-subtitle">Explorez notre collection complète de livres numériques</p>
        </div>

        {{-- Filtres par genre / catégorie avec images --}}
        <div class="mb-8">
            <h2 class="mb-4 text-sm font-black uppercase tracking-wider text-slate-400">Parcourir par catégorie</h2>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                {{-- Tous les genres --}}
                <a href="{{ route('dashboard.catalogue') }}" 
                   class="group relative flex h-28 flex-col items-center justify-center overflow-hidden rounded-2xl border-2 transition hover:-translate-y-1 hover:shadow-lg {{ !request('category') ? 'border-indigo-500 bg-indigo-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-indigo-300' }}">
                    <i class="fas fa-th-large text-xl mb-2 {{ !request('category') ? 'text-white' : 'text-indigo-500' }}"></i>
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
                    $cover = $category->ebooks()->whereNotNull('cover_path')->where('cover_path', '!=', '')->first();
                    $isActive = request('category') === $category->slug;
                    $grad = $catGradients[$category->slug] ?? 'from-indigo-500 to-violet-600';
                    $icon = $catIcons[$category->slug] ?? 'fa-book';
                @endphp
                <a href="{{ route('dashboard.catalogue', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                   class="group relative h-28 overflow-hidden rounded-2xl border-2 transition hover:-translate-y-1 hover:shadow-lg {{ $isActive ? 'border-indigo-500 ring-2 ring-indigo-500/30' : 'border-slate-200 hover:border-indigo-300' }}">
                    @if($cover)
                        <img src="{{ Storage::url($cover->cover_path) }}" alt="{{ $category->name }}"
                             class="absolute inset-0 h-full w-full object-cover transition duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br {{ $grad }}"></div>
                        <div class="absolute inset-0 flex items-center justify-center opacity-20">
                            <i class="fas {{ $icon }} text-5xl text-white"></i>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    @endif
                    <div class="absolute inset-x-0 bottom-0 p-3 text-center">
                        <h3 class="text-sm font-bold text-white drop-shadow">{{ $category->name }}</h3>
                        <p class="text-xs text-white/70">{{ $category->ebooks_count }} livre{{ $category->ebooks_count != 1 ? 's' : '' }}</p>
                    </div>
                    @if($isActive)
                    <div class="absolute top-2 right-2">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-indigo-500 text-white shadow">
                            <i class="fas fa-check text-xs"></i>
                        </span>
                    </div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>

        {{-- Filtres supplémentaires --}}
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('dashboard.catalogue', array_merge(request()->query(), ['type' => null])) }}"
                   class="pill-filter {{ !request('type') ? 'pill-active' : '' }}">
                    Tous
                </a>
                <a href="{{ route('dashboard.catalogue', array_merge(request()->query(), ['type' => 'free'])) }}"
                   class="pill-filter {{ request('type') === 'free' ? 'pill-active' : '' }}">
                    <i class="fas fa-gift mr-2"></i>Gratuits
                </a>
                <a href="{{ route('dashboard.catalogue', array_merge(request()->query(), ['type' => 'paid'])) }}"
                   class="pill-filter {{ request('type') === 'paid' ? 'pill-active' : '' }}">
                    <i class="fas fa-crown mr-2"></i>Payants
                </a>
            </div>
            
            <div class="flex items-center gap-3">
                {{-- Search --}}
                <form action="{{ route('dashboard.catalogue') }}" method="GET" class="flex items-center">
                    @foreach(request()->except(['search', 'page']) as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..."
                           class="w-40 rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                </form>
                
                {{-- Sort --}}
                <select onchange="window.location.href='{{ route('dashboard.catalogue', array_merge(request()->query(), ['sort' => 'SORT_VALUE'])) }}'.replace('SORT_VALUE', this.value)"
                        class="rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    <option value="">Trier par...</option>
                    <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Plus populaires</option>
                    <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Mieux notés</option>
                </select>
            </div>
        </div>

        {{-- Grid d'ebooks --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($ebooks as $ebook)
            <a href="{{ route('public.ebooks.show', $ebook) }}" class="premium-card group block">
                {{-- Cover --}}
                @if($ebook->cover_path)
                    <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}"
                         class="h-56 w-full rounded-t-[2rem] object-cover">
                @else
                    <div class="ebook-cover-placeholder h-56 rounded-t-[2rem] text-5xl">
                        <i class="fas fa-book"></i>
                    </div>
                @endif
                <div class="p-5">
                    <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 line-clamp-2 transition">
                        {{ $ebook->title }}
                    </h3>
                    <p class="mt-1 text-xs font-semibold text-slate-400">{{ $ebook->author }}</p>
                    
                    {{-- Rating --}}
                    <div class="mt-3 flex items-center gap-1">
                        @php $rating = round($ebook->reviews_avg_rating ?? 0); @endphp
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                        @endfor
                        <span class="ml-1 text-xs text-slate-400">({{ $ebook->reviews_count ?? 0 }})</span>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <span class="badge {{ $ebook->is_free ? 'badge-success' : 'badge-primary' }}">
                            {{ $ebook->is_free ? 'Gratuit' : number_format($ebook->price, 2, ',', ' ') . ' €' }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $ebook->category->name ?? '—' }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full py-16 text-center">
                <i class="fas fa-search mb-4 text-5xl text-slate-200"></i>
                <h3 class="text-lg font-bold text-slate-600">Aucun e-book trouvé</h3>
                <p class="mt-2 text-slate-400">Essayez de modifier vos filtres de recherche</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($ebooks->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $ebooks->appends(request()->query())->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</section>
@endsection
