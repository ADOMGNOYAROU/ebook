@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Nos E-books</h1>
        <div class="flex space-x-2">
            <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <option>Trier par</option>
                <option>Plus récents</option>
                <option>Plus populaires</option>
                <option>A-Z</option>
            </select>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.ebooks.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Ajouter un E-book
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <!-- Filtres par catégorie -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('public.ebooks.index') }}" class="px-4 py-2 rounded-full bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition">
                Tous
            </a>
            @foreach($categories as $category)
                <a href="{{ route('public.ebooks.index', ['category' => $category->id]) }}" 
                   class="px-4 py-2 rounded-full bg-gray-100 hover:bg-gray-200 transition flex items-center">
                    @if($category->icon)
                        <i class="{{ $category->icon }} mr-2"></i>
                    @endif
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Liste des ebooks -->
    @if($ebooks->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($ebooks as $ebook)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fas fa-book text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900">{{ $ebook->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $ebook->author }}</p>
                            </div>
                            <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded-full">
                                {{ $ebook->category->name }}
                            </span>
                        </div>
                        <p class="text-gray-600 text-sm mt-2 line-clamp-2">
                            {{ $ebook->description }}
                        </p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-download mr-1"></i> {{ $ebook->downloads_count }}
                            </span>
                            <a href="{{ route('public.ebooks.show', $ebook) }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                Voir plus <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8">
            {{ $ebooks->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <i class="fas fa-book-open text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-600">Aucun e-book trouvé pour le moment.</p>
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.ebooks.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Ajouter votre premier e-book
                    </a>
                @endif
            @endauth
        </div>
    @endif
</div>
@endsection
