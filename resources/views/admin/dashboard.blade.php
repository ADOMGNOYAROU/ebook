@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Tableau de bord administrateur</h1>
        <p class="mt-2 text-sm text-gray-600">Bienvenue dans votre espace d'administration</p>
    </div>

    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Nombre total d'ebooks -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Ebooks</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['totalEbooks']) }}</p>
                </div>
            </div>
        </div>

        <!-- Nombre total d'utilisateurs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Utilisateurs</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['totalUsers']) }}</p>
                </div>
            </div>
        </div>

        <!-- Nombre total de téléchargements -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Téléchargements</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['totalDownloads']) }}</p>
                </div>
            </div>
        </div>

        <!-- Nombre total de catégories -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Catégories</h3>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['totalCategories']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Derniers ajouts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Derniers ebooks ajoutés -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Derniers ebooks ajoutés</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($stats['recentEbooks'] as $ebook)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center">
                                <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $ebook->title }}</h4>
                                <p class="text-sm text-gray-500">Ajouté le {{ $ebook->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Aucun ebook trouvé
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-gray-50 text-right">
                <a href="{{ route('admin.ebooks.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Voir tous les ebooks →
                </a>
            </div>
        </div>

        <!-- Derniers utilisateurs inscrits -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Derniers utilisateurs inscrits</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($stats['recentUsers'] as $user)
                    <div class="p-4 hover:bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-500">Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500">
                        Aucun utilisateur trouvé
                    </div>
                @endforelse
            </div>
            <div class="px-6 py-3 bg-gray-50 text-right">
                <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                    Voir tous les utilisateurs →
                </a>
            </div>
        </div>
    </div>

    <!-- Liens rapides -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Actions rapides</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.ebooks.create') }}" class="group p-4 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-indigo-600">Ajouter un ebook</span>
                </div>
            </a>
            <a href="{{ route('admin.categories.create') }}" class="group p-4 border border-gray-200 rounded-lg hover:bg-green-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-green-100 text-green-600 group-hover:bg-green-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-green-600">Ajouter une catégorie</span>
                </div>
            </a>
            <a href="{{ route('admin.users.index') }}" class="group p-4 border border-gray-200 rounded-lg hover:bg-blue-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-blue-100 text-blue-600 group-hover:bg-blue-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-blue-600">Gérer les utilisateurs</span>
                </div>
            </a>
            <a href="{{ route('admin.stats') }}" class="group p-4 border border-gray-200 rounded-lg hover:bg-purple-50 transition-colors">
                <div class="flex items-center">
                    <div class="p-2 rounded-md bg-purple-100 text-purple-600 group-hover:bg-purple-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-purple-600">Voir les statistiques</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
