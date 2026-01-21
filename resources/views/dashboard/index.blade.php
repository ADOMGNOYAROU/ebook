@extends('layouts.dashboard')

@section('title', 'Tableau de bord')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
    <p class="text-gray-600">Bienvenue sur votre espace personnel</p>
</div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                    <i class="fas fa-book-open text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">E-books téléchargés</p>
                    <p class="text-2xl font-semibold">{{ $userDownloads->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Avis laissés</p>
                    <p class="text-2xl font-semibold">{{ $userReviews->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-heart text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Favoris</p>
                    <p class="text-2xl font-semibold">{{ $userFavorites->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Notifications</p>
                    <p class="text-2xl font-semibold">{{ $unreadNotifications->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Derniers téléchargements -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Derniers téléchargements</h2>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($userDownloads as $download)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            {{ $download->ebook->title }}
                                        </p>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <p class="text-xs text-gray-500">
                                                {{ $download->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">
                                        {{ $download->ebook->author }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-gray-500">
                            <p>Aucun téléchargement récent</p>
                        </div>
                    @endforelse
                </div>
                @if($userDownloads->count() > 5)
                    <div class="px-6 py-3 bg-gray-50 text-right">
                        <a href="{{ route('downloads.history') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Voir tout l'historique
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Activité récente -->
        <div>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Activité récente</h2>
                </div>
                <div class="flow-root">
                    <ul class="divide-y divide-gray-200">
                        @forelse($stats['recent_activity'] as $activity)
                            <li class="p-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        @if($activity->type === 'download')
                                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                                <i class="fas fa-download"></i>
                                            </div>
                                        @elseif($activity->type === 'review')
                                            <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                                                <i class="fas fa-star"></i>
                                            </div>
                                        @elseif($activity->type === 'favorite')
                                            <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-gray-800 truncate">
                                            {{ $activity->description }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="p-6 text-center text-gray-500">
                                <p>Aucune activité récente</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Suggestions pour vous</h2>
                </div>
                <div class="p-4">
                    @forelse($recommendedEbooks as $suggestion)
                        <div class="flex items-start mb-4">
                            <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-md overflow-hidden">
                                @if($suggestion->cover_image)
                                    <img src="{{ asset('storage/' . $suggestion->cover_image) }}" alt="{{ $suggestion->title }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-gray-400">
                                        <i class="fas fa-book"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">{{ $suggestion->title }}</h4>
                                <p class="text-xs text-gray-500">{{ $suggestion->author }}</p>
                                <a href="{{ route('public.ebooks.show', $suggestion) }}" class="mt-1 text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Aucune suggestion pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
