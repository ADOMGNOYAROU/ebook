@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes téléchargements</h1>
        <p class="mt-2 text-sm text-gray-600">Retrouvez l'historique complet de vos téléchargements d'e-books.</p>
    </div>

    <a href="{{ route('downloads.history') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
        <i class="fas fa-download mr-2"></i>
        Voir l'historique
    </a>
</div>
@endsection
