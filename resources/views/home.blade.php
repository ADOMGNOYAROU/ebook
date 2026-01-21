@extends('layouts.app')

@section('content')
<div class="bg-white" style="margin-top: 0; padding-top: 0;">
    <!-- Hero Section -->
    <div class="hero-section relative bg-indigo-700 overflow-hidden" style="margin-top: 0;">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 bg-indigo-700">
                <main class="mx-auto max-w-7xl px-4 py-2 sm:px-6 lg:px-8" style="padding-top: 0.5rem; padding-bottom: 1.5rem;">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                            <span class="block">Découvrez des milliers</span>
                            <span class="block text-indigo-200">d'e-books gratuits</span>
                        </h1>
                        <p class="mt-3 text-base text-indigo-100 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Téléchargez, lisez et partagez vos e-books préférés en quelques clics. Une bibliothèque numérique à portée de main.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ route('public.ebooks.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                                    Explorer la bibliothèque
                                </a>
                            </div>
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="#popular" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10">
                                    E-books populaires
                                </a>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
            <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1551029506-0807df4e2031?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" alt="Person reading a book">
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Catégories</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Explorez par catégorie
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Trouvez facilement des e-books qui correspondent à vos centres d'intérêt
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-2 gap-5 md:grid-cols-3 lg:grid-cols-4">
                <!-- Début du débogage -->
                @if($categories->isEmpty())
                    <div class="col-span-4 text-center py-8">
                        <p class="text-red-500 font-semibold">Aucune catégorie trouvée dans la base de données.</p>
                        <p class="text-sm text-gray-500 mt-2">Veuillez ajouter des catégories via la console tinker ou l'interface d'administration.</p>
                    </div>
                @else
                    @foreach($categories as $category)
                        <a href="{{ route('public.ebooks.index', ['category' => $category->slug]) }}" class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200 hover:shadow-lg transition duration-150 ease-in-out transform hover:-translate-y-1">
                            <div class="flex-1 flex flex-col p-8">
                                <div class="w-20 h-20 flex-shrink-0 mx-auto rounded-full bg-indigo-100 flex items-center justify-center">
                                    <i class="{{ $category->icon ?? 'fas fa-book' }} text-3xl text-indigo-600"></i>
                                </div>
                                <h3 class="mt-6 text-gray-900 text-sm font-medium">{{ $category->name }}</h3>
                                <dl class="mt-1 flex-grow flex flex-col justify-between">
                                    <dt class="sr-only">Nombre d'e-books</dt>
                                    <dd class="text-gray-500 text-sm">{{ $category->ebooks_count ?? 0 }} e-book{{ ($category->ebooks_count ?? 0) != 1 ? 's' : '' }}</dd>
                                </dl>
                            </div>
                        </a>
                    @endforeach
                @endif
                <!-- Fin du débogage -->
                </div>
            </div>
        </div>
    </div>

    <!-- Popular Ebooks -->
    <div id="popular" class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Populaires</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Les plus téléchargés
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Découvrez les e-books les plus populaires de notre bibliothèque
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($popularEbooks as $ebook)
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($ebook->cover_image)
                                            <img class="h-24 w-16 object-cover rounded-md" 
                                                 src="{{ asset('storage/' . $ebook->cover_image) }}" 
                                                 alt="{{ $ebook->title }}">
                                        @else
                                            <div class="h-24 w-16 bg-indigo-100 rounded-md flex items-center justify-center">
                                                <i class="fas fa-book text-indigo-400 text-2xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            <a href="{{ route('public.ebooks.show', $ebook) }}" class="hover:text-indigo-600">
                                                {{ Str::limit($ebook->title, 35) }}
                                            </a>
                                        </h3>
                                        <p class="text-sm text-gray-500">{{ $ebook->author }}</p>
                                        <div class="mt-2 flex items-center">
                                            @php
                                                $rating = $ebook->reviews_avg_rating ?? 0;
                                                $reviewCount = $ebook->reviews_count ?? 0;
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $rating)
                                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                @elseif($i - 0.5 <= $rating)
                                                    <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                                @else
                                                    <i class="far fa-star text-yellow-400 text-xs"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-1 text-xs text-gray-500">({{ $reviewCount }})</span>
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <i class="fas fa-download text-gray-400 mr-1"></i>
                                            <span>{{ $ebook->downloads_count }} téléchargements</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $ebook->category->name }}
                                    </span>
                                    <form action="{{ route('public.ebooks.download', $ebook) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-download mr-1"></i> Télécharger
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Ebooks -->
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:text-center">
                <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Nouveautés</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Derniers ajouts
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 lg:mx-auto">
                    Découvrez nos dernières publications
                </p>
            </div>

            <div class="mt-10">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($latestEbooks as $ebook)
                        <div class="bg-white overflow-hidden shadow rounded-lg">
                            <div class="p-5">
                                @if($ebook->cover_image)
                                    <img class="h-48 w-full object-cover rounded-md mb-4" 
                                         src="{{ asset('storage/' . $ebook->cover_image) }}" 
                                         alt="{{ $ebook->title }}">
                                @else
                                    <div class="h-48 bg-indigo-100 rounded-md flex items-center justify-center mb-4">
                                        <i class="fas fa-book text-indigo-400 text-5xl"></i>
                                    </div>
                                @endif
                                <h3 class="text-lg font-medium text-gray-900">
                                    <a href="{{ route('public.ebooks.show', $ebook) }}" class="hover:text-indigo-600">
                                        {{ Str::limit($ebook->title, 40) }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $ebook->author }}</p>
                                <div class="mt-2 flex items-center">
                                    @php
                                        $rating = $ebook->reviews_avg_rating ?? 0;
                                        $reviewCount = $ebook->reviews_count ?? 0;
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400 text-xs"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-1 text-xs text-gray-500">({{ $reviewCount }})</span>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-5 py-3">
                                <div class="flex justify-between items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $ebook->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $ebook->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Prêt à explorer notre bibliothèque ?</span>
                <span class="block">Commencez dès maintenant.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">
                Rejoignez des milliers de lecteurs qui découvrent déjà nos e-books gratuits.
            </p>
            <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                S'inscrire gratuitement
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
@endpush
@endsection
