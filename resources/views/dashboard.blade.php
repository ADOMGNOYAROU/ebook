@extends('layouts.app')
@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 sm:p-8 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Tableau de bord</h2>
                
                <!-- Statistiques -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
                    <!-- Nombre total de téléchargements -->
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Téléchargements</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $downloadCount }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dernier téléchargement -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Dernier téléchargement</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($lastDownload && $lastDownload->ebook)
                                        {{ $lastDownload->ebook->title }}
                                    @else
                                        Aucun téléchargement
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Ebooks favoris -->
                    <div class="bg-green-50 p-6 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Favoris</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $favoritesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Derniers téléchargements -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Derniers téléchargements</h3>
                    <div class="bg-white shadow overflow-hidden sm:rounded-md">
                        <ul class="divide-y divide-gray-200">
                            @forelse($recentDownloads as $download)
                                <li>
                                    <a href="#" class="block hover:bg-gray-50">
                                        <div class="px-4 py-4 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-medium text-indigo-600 truncate">
                                                    {{ $download->ebook->title ?? 'Ebook supprimé' }}
                                                </p>
                                                <div class="ml-2 flex-shrink-0 flex">
                                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $download->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="mt-2 sm:flex sm:justify-between">
                                                <div class="sm:flex">
                                                    <p class="flex items-center text-sm text-gray-500">
                                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $download->ebook->author ?? 'Auteur inconnu' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li class="p-4 text-center text-gray-500">
                                    Aucun téléchargement récent
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Ebooks recommandés -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recommandations pour vous</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @forelse($recommendedEbooks as $ebook)
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <div class="p-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <img class="h-12 w-12 rounded-md object-cover" src="{{ asset($ebook->cover_path) }}" alt="Couverture de {{ $ebook->title }}">
                                        </div>
                                        <div class="ml-4">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $ebook->title }}</h4>
                                            <p class="text-sm text-gray-500">{{ $ebook->author }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-between items-center">
                                        <span class="text-sm text-gray-500">{{ $ebook->downloads_count }} téléchargements</span>
                                        <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Voir</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Aucune recommandation pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
