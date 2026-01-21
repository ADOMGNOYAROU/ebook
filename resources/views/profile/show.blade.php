@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <!-- En-tête du profil -->
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    Mon Profil
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('profile.edit') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user-edit mr-2"></i> Modifier le profil
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Colonne de gauche : Informations du compte -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Informations du compte
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Photo de profil
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <div class="flex items-center">
                                        <div class="h-16 w-16 rounded-full overflow-hidden bg-gray-100">
                                            @if(Auth::user()->profile_photo_path)
                                                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" 
                                                     alt="{{ Auth::user()->name }}" 
                                                     class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-indigo-100 text-indigo-600">
                                                    <span class="text-2xl font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Nom complet
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ Auth::user()->name }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Adresse email
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ Auth::user()->email }}
                                </dd>
                            </div>
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Compte créé le
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ Auth::user()->created_at->format('d/m/Y') }}
                                    <span class="text-gray-500">({{ Auth::user()->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            @if(Auth::user()->last_login_at)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Dernière connexion
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ Auth::user()->last_login_at->format('d/m/Y à H:i') }}
                                    <span class="text-gray-500">({{ Auth::user()->last_login_at->diffForHumans() }})</span>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="mt-6 bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Activité
                        </h3>
                        <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    E-books téléchargés
                                </dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                    {{ $downloadsCount }}
                                </dd>
                                <dt class="mt-2 text-sm font-medium text-gray-500 truncate">
                                    Dernier téléchargement
                                </dt>
                                <dd class="text-sm text-gray-900">
                                    @if($lastDownload)
                                        {{ $lastDownload->created_at->diffForHumans() }}
                                    @else
                                        Jamais
                                    @endif
                                </dd>
                            </div>

                            <div class="px-4 py-5 bg-white shadow rounded-lg overflow-hidden sm:p-6">
                                <dt class="text-sm font-medium text-gray-500 truncate">
                                    Favoris
                                </dt>
                                <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                    {{ $favoritesCount }}
                                </dd>
                                <dt class="mt-2 text-sm font-medium text-gray-500 truncate">
                                    Avis laissés
                                </dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $reviewsCount }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite : Activité récente -->
            <div class="lg:col-span-2">
                <!-- Derniers téléchargements -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Derniers téléchargements
                        </h3>
                        <a href="{{ route('downloads.history') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Voir tout
                        </a>
                    </div>
                    <div class="border-t border-gray-200">
                        @if($recentDownloads->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentDownloads as $download)
                                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                        <div class="flex items-center">
                                            <div class="min-w-0 flex-1 flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center bg-indigo-100 rounded-md text-indigo-600">
                                                    <i class="fas fa-file-pdf"></i>
                                                </div>
                                                <div class="min-w-0 flex-1 px-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                                            <a href="{{ route('public.ebooks.show', $download->ebook->slug) }}">
                                                                {{ $download->ebook->title }}
                                                            </a>
                                                        </p>
                                                        <p class="mt-1 flex items-center text-sm text-gray-500">
                                                            <span class="truncate">{{ $download->ebook->author }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="ml-5 flex-shrink-0">
                                                <div class="text-sm text-gray-500">
                                                    {{ $download->created_at->diffForHumans() }}
                                                </div>
                                                <div class="mt-1">
                                                    <a href="{{ route('public.ebooks.download', $download->ebook) }}" 
                                                       class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                        Télécharger à nouveau
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-4 py-12 text-center">
                                <i class="fas fa-download text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Aucun téléchargement récent</p>
                                <div class="mt-4">
                                    <a href="{{ route('public.ebooks.index') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                        Parcourir la bibliothèque
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Derniers avis -->
                <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Mes avis récents
                        </h3>
                        @if($reviewsCount > 3)
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Voir tout
                            </a>
                        @endif
                    </div>
                    <div class="border-t border-gray-200">
                        @if($recentReviews->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($recentReviews as $review)
                                    <li class="px-4 py-4 sm:px-6">
                                        <div class="flex items-start">
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('ebooks.show', $review->ebook) }}" class="block">
                                                    <div class="h-12 w-10 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                                        @if($review->ebook->cover_image)
                                                            <img src="{{ asset('storage/' . $review->ebook->cover_image) }}" 
                                                                 alt="{{ $review->ebook->title }}" 
                                                                 class="h-full w-full object-cover">
                                                        @else
                                                            <i class="fas fa-book text-gray-400"></i>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="ml-4 flex-1">
                                                <div class="flex items-center justify-between">
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        <a href="{{ route('ebooks.show', $review->ebook) }}" class="hover:underline">
                                                            {{ $review->ebook->title }}
                                                        </a>
                                                    </h4>
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
                                                <div class="mt-1 text-sm text-gray-600">
                                                    {{ Str::limit($review->comment, 100) }}
                                                </div>
                                                <div class="mt-2 text-xs text-gray-500">
                                                    Posté {{ $review->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-4 py-12 text-center">
                                <i class="far fa-star text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Vous n'avez pas encore laissé d'avis</p>
                                <div class="mt-4">
                                    <a href="{{ route('downloads.history') }}" class="text-indigo-600 hover:text-indigo-500 font-medium">
                                        Voir mes téléchargements
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression de compte -->
<div id="deleteAccountModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Supprimer mon compte
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et toutes vos données seront définitivement supprimées.
                                </p>
                                <div class="mt-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700">
                                        Confirmez votre mot de passe pour continuer :
                                    </label>
                                    <input type="password" name="password" id="password" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Supprimer mon compte
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Fonction pour ouvrir la modal de suppression de compte
    function openDeleteModal() {
        document.getElementById('deleteAccountModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    // Fonction pour fermer la modal de suppression de compte
    function closeDeleteModal() {
        document.getElementById('deleteAccountModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Fermer avec la touche Échap
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endpush
@endsection
