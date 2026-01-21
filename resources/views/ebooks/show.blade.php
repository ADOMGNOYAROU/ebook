@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- En-tête avec image de couverture et informations de base -->
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Colonne de gauche : Couverture -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    @if($ebook->cover_image)
                        <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-auto">
                    @else
                        <div class="w-full h-96 bg-gray-100 flex items-center justify-center">
                            <i class="fas fa-book text-6xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <!-- Boutons d'action -->
                    <div class="p-4 space-y-3">
                        @auth
                            @if(auth()->user()->hasDownloaded($ebook->id))
                                <a href="{{ route('public.ebooks.download', $ebook) }}" 
                                   class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-download mr-2"></i> Télécharger à nouveau
                                </a>
                            @else
                                @if($ebook->is_free)
                                    <form action="{{ route('public.ebooks.download', $ebook) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                            <i class="fas fa-download mr-2"></i> Télécharger l'e-book
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('checkout', $ebook->id) }}" 
                                       class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                        <i class="fas fa-shopping-cart mr-2"></i> Acheter pour {{ number_format($ebook->price, 2, ',', ' ') }} €
                                    </a>
                                @endif
                            @endif
                            
                            <button type="button" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                                    onclick="document.getElementById('favorite-form').submit();">
                                @if(auth()->user()->hasFavorited($ebook->id))
                                    <i class="fas fa-heart text-red-500 mr-2"></i> Retirer des favoris
                                @else
                                    <i class="far fa-heart mr-2"></i> Ajouter aux favoris
                                @endif
                            </button>
                            <form id="favorite-form" action="{{ route('favorites.toggle', $ebook) }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        @else
                            @if($ebook->is_free)
                                <a href="{{ route('login') }}" 
                                   class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Connectez-vous pour télécharger
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <i class="fas fa-shopping-cart mr-2"></i> Acheter pour {{ number_format($ebook->price, 2, ',', ' ') }} €
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                
                <!-- Informations techniques -->
                <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Détails du livre</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Auteur</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->author }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Catégorie</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->category->name }}</dd>
                        </div>
                        @if($ebook->page_count)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Pages</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->page_count }}</dd>
                        </div>
                        @endif
                        @if($ebook->language)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Langue</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->language }}</dd>
                        </div>
                        @endif
                        @if($ebook->file_size)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Taille du fichier</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->file_size }} MB</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Format</dt>
                            <dd class="text-sm text-gray-900">PDF</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Téléchargements</dt>
                            <dd class="text-sm text-gray-900">{{ $ebook->downloads_count }}</dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Partage social -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900 mb-3">Partager</h3>
                    <div class="flex space-x-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('public.ebooks.show', $ebook->slug)) }}" 
                           target="_blank" 
                           class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Facebook</span>
                            <i class="fab fa-facebook text-2xl"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('public.ebooks.show', $ebook->slug)) }}&text={{ urlencode($ebook->title) }}" 
                           target="_blank" 
                           class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Twitter</span>
                            <i class="fab fa-twitter text-2xl"></i>
                        </a>
                        <a href="mailto:?subject={{ $ebook->title }}&body={{ urlencode(route('public.ebooks.show', $ebook->slug)) }}" 
                           class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Email</span>
                            <i class="fas fa-envelope text-2xl"></i>
                        </a>
                        <button onclick="copyToClipboard('{{ route('public.ebooks.show', $ebook->slug) }}')" 
                                class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Copier le lien</span>
                            <i class="fas fa-link text-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Colonne de droite : Détails -->
            <div class="mt-8 lg:mt-0 lg:col-span-2 lg:pl-8">
                <!-- En-tête avec titre et note -->
                <div class="mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $ebook->title }}</h1>
                        <div class="mt-2 sm:mt-0">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $averageRating)
                                            <i class="fas fa-star text-yellow-400"></i>
                                        @elseif($i - 0.5 <= $averageRating)
                                            <i class="fas fa-star-half-alt text-yellow-400"></i>
                                        @else
                                            <i class="far fa-star text-yellow-400"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="ml-2 text-sm text-gray-500">({{ $reviews->count() }} avis)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                        <span>Par {{ $ebook->author }}</span>
                        <span class="mx-2">•</span>
                        <span>Publié le {{ $ebook->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <!-- Description -->
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Description</h2>
                    <div class="prose max-w-none text-gray-500">
                        {!! nl2br(e($ebook->description)) !!}
                    </div>
                </div>
                
                <!-- Mots-clés -->
                @if($ebook->keywords && count(json_decode($ebook->keywords, true)) > 0)
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">Mots-clés</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach(json_decode($ebook->keywords, true) as $keyword)
                            <a href="{{ route('public.ebooks.index', ['search' => $keyword]) }}" 
                               class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200">
                                {{ $keyword }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Avis -->
                <div class="mt-12">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium text-gray-900">Avis des lecteurs</h2>
                        @auth
                            @if(!$userReview && auth()->user()->hasDownloaded($ebook->id))
                                <button type="button" 
                                        onclick="document.getElementById('review-form').classList.toggle('hidden'); window.scrollBy(0, 200);"
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Laisser un avis
                                </button>
                            @endif
                        @endauth
                    </div>
                    
                    <!-- Formulaire d'avis (caché par défaut) -->
                    @auth
                        @if(!$userReview && auth()->user()->hasDownloaded($ebook->id))
                            <div id="review-form" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                                <h3 class="text-md font-medium text-gray-900 mb-3">Votre avis</h3>
                                <form action="{{ route('reviews.store', $ebook) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" 
                                                        onclick="setRating({{ $i }})" 
                                                        class="text-2xl focus:outline-none"
                                                        id="star-{{ $i }}">
                                                    <i class="far fa-star text-yellow-400"></i>
                                                </button>
                                            @endfor
                                            <input type="hidden" name="rating" id="rating" value="0" required>
                                        </div>
                                        @error('rating')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="block text-sm font-medium text-gray-700">Commentaire</label>
                                        <textarea id="comment" name="comment" rows="3" 
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                  required></textarea>
                                        @error('comment')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" 
                                                onclick="document.getElementById('review-form').classList.add('hidden')" 
                                                class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            Annuler
                                        </button>
                                        <button type="submit" 
                                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                            Publier l'avis
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth
                    
                    <!-- Liste des avis -->
                    <div class="mt-6 space-y-8">
                        @if($reviews->count() > 0)
                            @foreach($reviews as $review)
                                <div class="border-b border-gray-200 pb-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                                {{ substr($review->user->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $review->user->name }}</p>
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                                                        @else
                                                            <i class="far fa-star text-yellow-400 text-xs"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                    <div class="mt-3 text-sm text-gray-600">
                                        {{ $review->comment }}
                                    </div>
                                    @auth
                                        @if(auth()->id() === $review->user_id || auth()->user()->is_admin)
                                            <div class="mt-2 text-right">
                                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800" 
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                            
                            {{ $reviews->links() }}
                        @else
                            <p class="text-gray-500 text-center py-4">Aucun avis pour le moment. Soyez le premier à donner votre avis !</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ebooks similaires -->
        @if($similarEbooks->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">E-books similaires</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($similarEbooks as $relatedEbook)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <a href="{{ route('public.ebooks.show', $relatedEbook) }}" class="block">
                            <div class="h-48 bg-gray-200 overflow-hidden">
                                @if($relatedEbook->cover_image)
                                    <img src="{{ asset('storage/' . $relatedEbook->cover_image) }}" alt="{{ $relatedEbook->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                        <i class="fas fa-book text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-900">{{ $relatedEbook->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $relatedEbook->author }}</p>
                                <div class="mt-2 flex justify-between items-center">
                                    <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full">
                                        {{ $relatedEbook->category->name }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        <i class="fas fa-download mr-1"></i> {{ $relatedEbook->downloads_count }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Fonction pour définir la note
    function setRating(rating) {
        // Mettre à jour les étoiles
        for (let i = 1; i <= 5; i++) {
            const star = document.getElementById(`star-${i}`);
            if (i <= rating) {
                star.innerHTML = '<i class="fas fa-star text-yellow-400"></i>';
            } else {
                star.innerHTML = '<i class="far fa-star text-yellow-400"></i>';
            }
        }
        
        // Mettre à jour la valeur du champ caché
        document.getElementById('rating').value = rating;
    }
    
    // Fonction pour copier le lien dans le presse-papier
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Afficher un message de succès (vous pouvez utiliser une bibliothèque comme Toastr)
            alert('Lien copié dans le presse-papier !');
        }, function() {
            alert('Impossible de copier le lien');
        });
    }
    
    // Initialisation des tooltips (si nécessaire)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips avec Tippy.js ou autre bibliothèque si nécessaire
    });
</script>
@endpush

@endsection
