@extends('layouts.app')

@section('content')

{{-- ===== HERO ===== --}}
<section class="relative overflow-hidden px-4 pb-20 pt-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div>
                <span class="section-eyebrow"><i class="fas fa-bolt mr-2"></i>Plateforme SaaS d'e-books</span>
                <h1 class="section-title">
                    Votre bibliothèque<br>
                    <span class="bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 bg-clip-text text-transparent">numérique illimitée</span>
                </h1>
                <p class="section-subtitle">
                    Accédez à des milliers d'e-books gratuits et premium. Téléchargez, lisez et organisez votre collection en quelques clics.
                </p>
                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('public.ebooks.index') }}" class="btn-primary">
                        <i class="fas fa-compass mr-2"></i>Explorer le catalogue
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="btn-secondary">
                        <i class="fas fa-user-plus mr-2"></i>Créer un compte gratuit
                    </a>
                    @endguest
                </div>
                {{-- Social proof --}}
                <div class="mt-12 flex items-center gap-6 border-t border-slate-100 pt-8">
                    <div class="text-center">
                        <p class="text-2xl font-black text-slate-950">{{ number_format(\App\Models\Ebook::count()) }}+</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">E-books</p>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-slate-950">{{ number_format(\App\Models\Download::count()) }}+</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Téléchargements</p>
                    </div>
                    <div class="h-8 w-px bg-slate-200"></div>
                    <div class="text-center">
                        <p class="text-2xl font-black text-slate-950">{{ number_format(\App\Models\Category::count()) }}</p>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Catégories</p>
                    </div>
                </div>
            </div>
            {{-- Hero visual --}}
            <div class="relative hidden lg:block">
                <div class="absolute -inset-4 rounded-[3rem] bg-gradient-to-br from-indigo-600/20 via-violet-600/10 to-fuchsia-600/20 blur-3xl"></div>
                <div class="relative grid grid-cols-2 gap-4">
                    @foreach($popularEbooks->take(4) as $ebook)
                    <a href="{{ route('public.ebooks.show', $ebook) }}" class="premium-card group p-4">
                        @if($ebook->cover_path)
                            <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}" class="h-36 w-full rounded-2xl object-cover">
                        @else
                            <div class="ebook-cover-placeholder h-36 w-full"><i class="fas fa-book text-4xl"></i></div>
                        @endif
                        <p class="mt-3 text-xs font-black text-slate-800 line-clamp-1">{{ $ebook->title }}</p>
                        <p class="text-xs text-slate-400">{{ $ebook->author }}</p>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
@php
$categoryImages = [
    'roman'                   => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&h=300&fit=crop',
    'science-fiction'         => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=400&h=300&fit=crop',
    'fantasy'                 => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=400&h=300&fit=crop',
    'policier'                => 'https://images.unsplash.com/photo-1509475826633-fed577a2c71b?w=400&h=300&fit=crop',
    'biographie'              => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=400&h=300&fit=crop',
    'developpement-personnel' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=400&h=300&fit=crop',
    'histoire'                => 'https://images.unsplash.com/photo-1461360370896-922624d12aa1?w=400&h=300&fit=crop',
    'science'                 => 'https://images.unsplash.com/photo-1532094349884-543559059673?w=400&h=300&fit=crop',
];
$categoryGradients = [
    'roman'                   => 'from-amber-600/80 to-orange-700/80',
    'science-fiction'         => 'from-blue-600/80 to-cyan-700/80',
    'fantasy'                 => 'from-violet-600/80 to-purple-800/80',
    'policier'                => 'from-slate-700/80 to-gray-900/80',
    'biographie'              => 'from-emerald-600/80 to-teal-700/80',
    'developpement-personnel' => 'from-rose-500/80 to-pink-700/80',
    'histoire'                => 'from-yellow-600/80 to-amber-800/80',
    'science'                 => 'from-indigo-600/80 to-blue-800/80',
];
@endphp
<section class="bg-slate-50/80 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="text-center">
            <span class="section-eyebrow">Explorer</span>
            <h2 class="section-title">Parcourir par catégorie</h2>
            <p class="section-subtitle mx-auto">Trouvez exactement ce que vous cherchez parmi nos catégories</p>
        </div>

        <div class="mt-14 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
            @forelse($categories as $category)
            @php
                $covers = $category->ebooks()->whereNotNull('cover_path')->take(3)->pluck('cover_path');
                $cnt = $covers->count();
            @endphp
            <a href="{{ route('public.ebooks.index', ['category' => $category->slug]) }}"
               class="group flex flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">

                {{-- Étagère de couvertures --}}
                <div class="relative flex h-40 items-end justify-center gap-2 overflow-hidden bg-gradient-to-b from-slate-100 to-slate-200 px-3 pb-0">
                    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-transparent to-slate-200/80"></div>

                    @if($cnt === 0)
                        <div class="relative flex h-32 w-24 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-200 to-violet-300 shadow-lg">
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
                        <p class="text-xs text-slate-400">{{ $category->ebooks_count ?? 0 }} livre{{ ($category->ebooks_count ?? 0) != 1 ? 's' : '' }}</p>
                    </div>
                    <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 transition group-hover:bg-indigo-600 group-hover:text-white">
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-4 py-12 text-center text-slate-500">
                <i class="fas fa-folder-open mb-3 text-3xl text-slate-300"></i>
                <p class="font-semibold">Aucune catégorie disponible</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== POPULAR EBOOKS ===== --}}
<section id="popular" class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex items-end justify-between">
            <div>
                <span class="section-eyebrow">Top</span>
                <h2 class="section-title">Les plus téléchargés</h2>
            </div>
            <a href="{{ route('public.ebooks.index', ['sort' => 'popular']) }}" class="btn-secondary hidden sm:inline-flex">
                Voir tout <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($popularEbooks as $ebook)
            <div class="ebook-card group flex gap-5 p-5">
                {{-- Cover --}}
                <div class="flex-shrink-0">
                    @if($ebook->cover_path)
                        <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}"
                             class="h-28 w-20 rounded-2xl object-cover shadow-lg shadow-slate-200">
                    @else
                        <div class="ebook-cover-placeholder h-28 w-20 text-2xl"></div>
                    @endif
                </div>
                {{-- Info --}}
                <div class="flex flex-1 flex-col justify-between">
                    <div>
                        <a href="{{ route('public.ebooks.show', $ebook) }}"
                           class="text-sm font-black text-slate-900 hover:text-indigo-600 line-clamp-2 transition">
                            {{ $ebook->title }}
                        </a>
                        <p class="mt-1 text-xs font-semibold text-slate-400">{{ $ebook->author }}</p>
                        {{-- Stars --}}
                        <div class="mt-2 flex items-center gap-1">
                            @php $rating = round($ebook->reviews_avg_rating ?? 0); @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                            @endfor
                            <span class="ml-1 text-xs text-slate-400">({{ $ebook->reviews_count ?? 0 }})</span>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <span class="badge badge-primary">{{ $ebook->category->name ?? '—' }}</span>
                        <span class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                            <i class="fas fa-download"></i>{{ number_format($ebook->downloads_count) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== LATEST EBOOKS ===== --}}
<section class="bg-slate-50/80 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex items-end justify-between">
            <div>
                <span class="section-eyebrow">Nouveautés</span>
                <h2 class="section-title">Derniers ajouts</h2>
            </div>
            <a href="{{ route('public.ebooks.index', ['sort' => 'latest']) }}" class="btn-secondary hidden sm:inline-flex">
                Voir tout <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($latestEbooks as $ebook)
            <a href="{{ route('public.ebooks.show', $ebook) }}" class="premium-card group block">
                {{-- Cover --}}
                @if($ebook->cover_path)
                    <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}"
                         class="h-48 w-full rounded-t-[2rem] object-cover">
                @else
                    <div class="ebook-cover-placeholder h-48 rounded-t-[2rem] text-5xl">
                    </div>
                @endif
                <div class="p-5">
                    <h3 class="text-sm font-black text-slate-900 group-hover:text-indigo-600 line-clamp-2 transition">
                        {{ $ebook->title }}
                    </h3>
                    <p class="mt-1 text-xs font-semibold text-slate-400">{{ $ebook->author }}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="badge {{ $ebook->is_free ? 'badge-success' : 'badge-primary' }}">
                            {{ $ebook->is_free ? 'Gratuit' : 'Premium' }}
                        </span>
                        <span class="text-xs text-slate-400">{{ $ebook->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA ===== --}}
@guest
<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-slate-950 via-indigo-950 to-violet-950 px-8 py-16 text-center shadow-2xl shadow-indigo-950/40">
        <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/10 text-white backdrop-blur">
            <i class="fas fa-rocket text-2xl"></i>
        </div>
        <h2 class="text-3xl font-black text-white sm:text-4xl">
            Rejoignez des milliers de lecteurs
        </h2>
        <p class="mx-auto mt-4 max-w-xl text-base text-indigo-200">
            Créez votre compte gratuitement et accédez immédiatement à notre bibliothèque de livres numériques.
        </p>
        <div class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center rounded-full bg-white px-8 py-4 text-sm font-black text-indigo-950 shadow-xl transition hover:-translate-y-0.5 hover:shadow-2xl">
                <i class="fas fa-user-plus mr-2"></i>Créer un compte gratuit
            </a>
            <a href="{{ route('public.ebooks.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-white/20 bg-white/10 px-8 py-4 text-sm font-black text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/20">
                <i class="fas fa-compass mr-2"></i>Explorer d'abord
            </a>
        </div>
    </div>
</section>
@endguest

@push('scripts')
<script>
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const el = document.querySelector(this.getAttribute('href'));
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
</script>
@endpush
@endsection
