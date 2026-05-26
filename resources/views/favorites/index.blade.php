@extends('layouts.dashboard')

@section('title', 'Mes Favoris')
@section('subtitle', 'Vos e-books préférés')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes Favoris</h1>
        <p class="mt-2 text-sm text-gray-600">Retrouvez ici tous les e-books que vous avez ajoutés à vos favoris.</p>
        
        @if(session('success'))
            <div class="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle h-5 w-5 text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($favorites->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <ul class="divide-y divide-gray-200">
                @foreach($favorites as $favorite)
                    <li class="hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="flex-shrink-0 h-16 w-12 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                        @if($favorite->ebook->cover_image)
                                            <img src="{{ asset('storage/' . $favorite->ebook->cover_image) }}" 
                                                 alt="{{ $favorite->ebook->title }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <i class="fas fa-book text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-medium text-indigo-600">
                                                <a href="{{ route('public.ebooks.show', $favorite->ebook->slug) }}" class="hover:underline">
                                                    {{ $favorite->ebook->title }}
                                                </a>
                                            </h3>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <form action="{{ route('favorites.toggle', $favorite->ebook) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            title="Retirer des favoris">
                                                        <i class="fas fa-heart"></i>
                                                        <span class="sr-only">Retirer des favoris</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Par {{ $favorite->ebook->author }}
                                            <span class="mx-2">•</span>
                                            {{ $favorite->ebook->page_count ?? 'N/A' }} pages
                                            <span class="mx-2">•</span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $favorite->ebook->category->name }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500">
                                            <div class="flex items-center mr-4">
                                                <i class="fas fa-download text-gray-400 mr-1"></i>
                                                <span>{{ $favorite->ebook->downloads_count }} téléchargements</span>
                                            </div>
                                            <div class="flex items-center">
                                                @php
                                                    $avgRating = $favorite->ebook->reviews_avg_rating;
                                                    $reviewsCount = $favorite->ebook->reviews_count;
                                                @endphp
                                                @if($reviewsCount > 0)
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $avgRating)
                                                                <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                            @elseif($i - 0.5 <= $avgRating)
                                                                <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                                                            @else
                                                                <i class="far fa-star text-yellow-400 text-xs"></i>
                                                            @endif
                                                        @endfor
                                                        <span class="ml-1 text-xs text-gray-500">({{ $reviewsCount }})</span>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-500">Pas encore noté</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-4 flex-shrink-0">
                                    @auth
                                        @if(auth()->user()->hasDownloaded($favorite->ebook->id))
                                            <a href="{{ route('public.ebooks.download', $favorite->ebook) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                <i class="fas fa-download mr-1"></i> Télécharger
                                            </a>
                                        @else
                                            <form action="{{ route('public.ebooks.download', $favorite->ebook) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <i class="fas fa-download mr-1"></i> Télécharger
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <i class="fas fa-sign-in-alt mr-1"></i> Connectez-vous
                                        </a>
                                    @endauth
                                </div>
                            </div>
                            <div class="mt-2">
                                @if($favorite->ebook->description)
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $favorite->ebook->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <!-- Pagination -->
            @if($favorites->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($favorites->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Précédent
                            </span>
                        @else
                            <a href="{{ $favorites->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Précédent
                            </a>
                        @endif
                        
                        @if($favorites->hasMorePages())
                            <a href="{{ $favorites->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Suivant
                            </a>
                        @else
                            <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Suivant
                            </span>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Affichage de
                                <span class="font-medium">{{ $favorites->firstItem() }}</span>
                                à
                                <span class="font-medium">{{ $favorites->lastItem() }}</span>
                                sur
                                <span class="font-medium">{{ $favorites->total() }}</span>
                                résultats
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <!-- Previous Page Link -->
                                @if($favorites->onFirstPage())
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Précédent</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </span>
                                @else
                                    <a href="{{ $favorites->previousPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Précédent</span>
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                @endif

                                <!-- Pagination Elements -->
                                @for($i = 1; $i <= $favorites->lastPage(); $i++)
                                    @if($i == $favorites->currentPage())
                                        <span class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ $favorites->url($i) }}" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor

                                <!-- Next Page Link -->
                                @if($favorites->hasMorePages())
                                    <a href="{{ $favorites->nextPageUrl() }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Suivant</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300">
                                        <span class="sr-only">Suivant</span>
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-12 text-center sm:px-6">
                <i class="far fa-heart text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Aucun favori pour le moment</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez à ajouter des e-books à vos favoris pour les retrouver facilement plus tard.</p>
                <div class="mt-6">
                    <a href="{{ route('public.ebooks.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-book-open mr-2"></i> Parcourir la bibliothèque
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Fonction pour confirmer la suppression d'un favori
    function confirmRemoveFavorite(ebookId) {
        if (confirm('Êtes-vous sûr de vouloir retirer cet e-book de vos favoris ?')) {
            document.getElementById('remove-favorite-form-' + ebookId).submit();
        }
    }
</script>
@endpush
@endsection
