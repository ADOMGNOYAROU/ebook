@extends('layouts.dashboard')

@section('title', 'Téléchargements')
@section('subtitle', 'Historique de vos téléchargements')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Historique des Téléchargements</h1>
        <p class="mt-2 text-sm text-gray-600">Retrouvez l'historique complet de vos téléchargements d'e-books.</p>
        
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

    @if($downloads->count() > 0)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $downloads->total() }} téléchargement{{ $downloads->total() > 1 ? 's' : '' }} au total
                        </h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Dernier téléchargement : {{ $downloads->first()->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <div class="relative">
                            <select id="filter" onchange="window.location.href=this.value" class="block appearance-none w-full bg-white border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-md leading-tight focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="{{ route('downloads.history') }}" {{ request('filter') == 'all' || !request('filter') ? 'selected' : '' }}>Tous les téléchargements</option>
                                <option value="{{ route('downloads.history', ['filter' => 'last_month']) }}" {{ request('filter') == 'last_month' ? 'selected' : '' }}>30 derniers jours</option>
                                <option value="{{ route('downloads.history', ['filter' => 'last_year']) }}" {{ request('filter') == 'last_year' ? 'selected' : '' }}>Cette année</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <ul class="divide-y divide-gray-200">
                @foreach($downloads as $download)
                    <li class="hover:bg-gray-50">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="flex-shrink-0 h-16 w-12 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                        @if($download->ebook->cover_image)
                                            <img src="{{ asset('storage/' . $download->ebook->cover_image) }}" 
                                                 alt="{{ $download->ebook->title }}" 
                                                 class="h-full w-full object-cover">
                                        @else
                                            <i class="fas fa-book text-gray-400 text-xl"></i>
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="text-lg font-medium text-indigo-600">
                                                <a href="{{ route('public.ebooks.show', $download->ebook->slug) }}" class="hover:underline">
                                                    {{ $download->ebook->title }}
                                                </a>
                                            </h3>
                                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ $download->ebook->category->name }}
                                            </span>
                                        </div>
                                        <div class="mt-1 text-sm text-gray-500">
                                            Par {{ $download->ebook->author }}
                                        </div>
                                        <div class="mt-2 flex flex-wrap items-center text-sm text-gray-500">
                                            <div class="flex items-center mr-4">
                                                <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                                <span title="{{ $download->created_at->format('d/m/Y à H:i') }}">
                                                    {{ $download->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="flex items-center mr-4">
                                                <i class="fas fa-file-pdf text-red-400 mr-1"></i>
                                                <span>{{ $download->ebook->file_size_formatted ?? 'N/A' }}</span>
                                            </div>
                                            @if($download->ip_address)
                                                <div class="flex items-center">
                                                    <i class="fas fa-laptop-house text-gray-400 mr-1"></i>
                                                    <span>{{ $download->ip_address }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 sm:mt-0 sm:ml-6 flex items-center"> 
                                    <div class="flex space-x-2">
                                        <a href="{{ route('public.ebooks.download', $download->ebook) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                           title="Télécharger à nouveau">
                                            <i class="fas fa-redo-alt mr-1"></i>
                                        </a>
                                        <a href="{{ route('public.ebooks.show', $download->ebook->slug) }}" 
                                           class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                           title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!auth()->user()->hasFavorited($download->ebook->id))
                                            <form action="{{ route('favorites.toggle', $download->ebook) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                        title="Ajouter aux favoris">
                                                    <i class="far fa-heart"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-red-300 text-xs font-medium rounded-md text-red-700 bg-red-50 focus:outline-none"
                                                    title="Déjà dans les favoris">
                                                <i class="fas fa-heart text-red-500"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($download->ebook->description)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $download->ebook->description }}
                                    </p>
                                </div>
                            @endif
                            
                            @if($download->review)
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <div class="flex items-center">
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $download->review->rating)
                                                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-yellow-400 text-sm"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-gray-900">{{ $download->review->rating }}.0</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">
                                        "{{ Str::limit($download->review->comment, 150) }}"
                                    </p>
                                    <div class="mt-1 text-xs text-gray-500">
                                        Avis laissé {{ $download->review->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @else
                                <div class="mt-3 pt-3 border-t border-gray-100">
                                    <button type="button" 
                                            onclick="openReviewModal({{ $download->ebook->id }}, '{{ route('reviews.store') }}')"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="far fa-star mr-1"></i> Laisser un avis
                                    </button>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
            
            <!-- Pagination -->
            @if($downloads->hasPages())
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($downloads->onFirstPage())
                            <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-white">
                                Précédent
                            </span>
                        @else
                            <a href="{{ $downloads->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Précédent
                            </a>
                        @endif
                        
                        @if($downloads->hasMorePages())
                            <a href="{{ $downloads->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <span class="font-medium">{{ $downloads->firstItem() }}</span>
                                à
                                <span class="font-medium">{{ $downloads->lastItem() }}</span>
                                sur
                                <span class="font-medium">{{ $downloads->total() }}</span>
                                résultats
                            </p>
                        </div>
                        <div>
                            {{ $downloads->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-12 text-center sm:px-6">
                <i class="fas fa-download text-5xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900">Aucun téléchargement</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez à télécharger des e-books pour les retrouver ici.</p>
                <div class="mt-6">
                    <a href="{{ route('public.ebooks.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-book-open mr-2"></i> Parcourir la bibliothèque
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modal pour laisser un avis -->
<div id="reviewModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="reviewForm" method="POST">
                @csrf
                <input type="hidden" name="ebook_id" id="ebookId">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-star text-indigo-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Donner votre avis
                            </h3>
                            <div class="mt-4
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Note
                                    </label>
                                    <div class="flex items-center" id="rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="far fa-star text-2xl text-yellow-400 cursor-pointer hover:text-yellow-500" 
                                               data-rating="{{ $i }}" 
                                               onmouseover="highlightStars(this)" 
                                               onmouseout="resetStars()" 
                                               onclick="setRating(this)"></i>
                                        @endfor
                                        <input type="hidden" name="rating" id="ratingValue" value="5" required>
                                    </div>
                                    @error('rating')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                                        Commentaire
                                    </label>
                                    <textarea id="comment" name="comment" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="Décrivez votre expérience avec cet e-book..."></textarea>
                                    @error('comment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Envoyer l'avis
                    </button>
                    <button type="button" onclick="closeReviewModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Variables globales pour la modale d'avis
    let currentRating = 0;
    
    // Fonction pour ouvrir la modale d'avis
    function openReviewModal(ebookId, actionUrl) {
        document.getElementById('ebookId').value = ebookId;
        document.getElementById('reviewForm').action = actionUrl;
        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Réinitialiser la sélection des étoiles
        resetStars();
        document.getElementById('ratingValue').value = 5;
        currentRating = 5;
    }
    
    // Fonction pour fermer la modale d'avis
    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Fonction pour mettre en surbrillance les étoiles au survol
    function highlightStars(star) {
        const rating = parseInt(star.getAttribute('data-rating'));
        const stars = document.querySelectorAll('#rating i');
        
        stars.forEach((s, index) => {
            if (index < rating) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });
    }
    
    // Fonction pour réinitialiser les étoiles
    function resetStars() {
        const stars = document.querySelectorAll('#rating i');
        
        stars.forEach((s, index) => {
            if (index < currentRating) {
                s.classList.remove('far');
                s.classList.add('fas');
            } else {
                s.classList.remove('fas');
                s.classList.add('far');
            }
        });
    }
    
    // Fonction pour définir la note
    function setRating(star) {
        currentRating = parseInt(star.getAttribute('data-rating'));
        document.getElementById('ratingValue').value = currentRating;
        resetStars();
    }
    
    // Fermer la modale avec la touche Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeReviewModal();
        }
    });
    
    // Gérer la soumission du formulaire avec AJAX
    document.getElementById('reviewForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeReviewModal();
                window.location.reload();
            } else {
                // Afficher les erreurs de validation
                console.error('Erreur lors de la soumission de l\'avis');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    });
</script>
@endpush
@endsection
