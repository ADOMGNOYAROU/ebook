@extends('layouts.dashboard')

@section('title', 'Mon Profil')
@section('subtitle', 'Gérez vos informations personnelles')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon profil</h1>
        <p class="mt-2 text-sm text-gray-600">Gérez les informations de votre compte.</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-sm font-medium text-gray-500">Nom</div>
                    <div class="mt-1 text-gray-900">{{ $user->name }}</div>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-500">Email</div>
                    <div class="mt-1 text-gray-900">{{ $user->email }}</div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-6 flex flex-col sm:flex-row sm:items-center gap-3">
                <a href="{{ route('favorites.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-bookmark mr-2"></i>
                    Mes favoris
                </a>
                <a href="{{ route('downloads.history') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-download mr-2"></i>
                    Mes téléchargements
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
