@extends('layouts.app')

@section('title', 'Ajouter un E-book')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Ajouter un nouvel E-book</h1>
        <p class="mt-2 text-gray-600">Remplissez les informations ci-dessous pour ajouter un nouvel e-book à la plateforme.</p>
    </div>

    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.ebooks.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <!-- Titre -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="title" 
                       id="title" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('title') }}"
                       required>
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Auteur -->
            <div class="mb-6">
                <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                    Auteur <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="author" 
                       id="author" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('author') }}"
                       required>
                @error('author')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" 
                          id="description" 
                          rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                          required>{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catégorie -->
            <div class="mb-6">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Catégorie <span class="text-red-500">*</span>
                </label>
                <select name="category_id" 
                        id="category_id" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                    <option value="">Sélectionner une catégorie</option>
                    @foreach($categories as $id => $name)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- ISBN -->
            <div class="mb-6">
                <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                    ISBN
                </label>
                <input type="text" 
                       name="isbn" 
                       id="isbn" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('isbn') }}"
                       placeholder="978-0-00-000000-0">
                @error('isbn')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Langue -->
            <div class="mb-6">
                <label for="language" class="block text-sm font-medium text-gray-700 mb-2">
                    Langue <span class="text-red-500">*</span>
                </label>
                <select name="language" 
                        id="language" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        required>
                    <option value="">Sélectionner une langue</option>
                    <option value="fr" {{ old('language') == 'fr' ? 'selected' : '' }}>Français</option>
                    <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>Anglais</option>
                    <option value="es" {{ old('language') == 'es' ? 'selected' : '' }}>Espagnol</option>
                    <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>Allemand</option>
                    <option value="it" {{ old('language') == 'it' ? 'selected' : '' }}>Italien</option>
                    <option value="pt" {{ old('language') == 'pt' ? 'selected' : '' }}>Portugais</option>
                    <option value="nl" {{ old('language') == 'nl' ? 'selected' : '' }}>Néerlandais</option>
                    <option value="zh" {{ old('language') == 'zh' ? 'selected' : '' }}>Chinois</option>
                    <option value="ja" {{ old('language') == 'ja' ? 'selected' : '' }}>Japonais</option>
                    <option value="ar" {{ old('language') == 'ar' ? 'selected' : '' }}>Arabe</option>
                </select>
                @error('language')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nombre de pages -->
            <div class="mb-6">
                <label for="pages" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre de pages
                </label>
                <input type="number" 
                       name="pages" 
                       id="pages" 
                       min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('pages') }}"
                       placeholder="250">
                @error('pages')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Année de publication -->
            <div class="mb-6">
                <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-2">
                    Année de publication
                </label>
                <input type="number" 
                       name="publication_year" 
                       id="publication_year" 
                       min="1900"
                       max="{{ date('Y') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('publication_year') ?? date('Y') }}"
                       placeholder="{{ date('Y') }}">
                @error('publication_year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="is_free" value="1" {{ old('is_free', '1') == '1' ? 'checked' : '' }} class="mr-2" required>
                        <span class="text-sm text-gray-700">Gratuit</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="is_free" value="0" {{ old('is_free') == '0' ? 'checked' : '' }} class="mr-2" required>
                        <span class="text-sm text-gray-700">Payant</span>
                    </label>
                </div>
                @error('is_free')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Fichier PDF -->
            <div class="mb-6">
                <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                    Fichier PDF <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center space-x-4">
                    <input type="file" 
                           name="file" 
                           id="file" 
                           accept=".pdf"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                           required>
                    <div class="text-sm text-gray-500">
                        <p>Format: PDF</p>
                        <p>Taille max: 50MB</p>
                    </div>
                </div>
                @error('file')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image de couverture -->
            <div class="mb-6">
                <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                    Image de couverture
                </label>
                <div class="flex items-center space-x-4">
                    <input type="file" 
                           name="cover_image" 
                           id="cover_image" 
                           accept="image/*"
                           class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="text-sm text-gray-500">
                        <p>Formats: JPG, PNG, GIF</p>
                        <p>Taille max: 5MB</p>
                    </div>
                </div>
                @error('cover_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tags -->
            <div class="mb-6">
                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                    Tags
                </label>
                <input type="text" 
                       name="tags" 
                       id="tags" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                       value="{{ old('tags') }}"
                       placeholder="romance, science-fiction, thriller">
                <p class="mt-1 text-sm text-gray-500">Séparez les tags par des virgules</p>
                @error('tags')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Statut <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="status" value="published" {{ old('status') == 'published' ? 'checked' : '' }} class="mr-2" required>
                        <span class="text-sm text-gray-700">Publié</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="status" value="draft" {{ old('status') == 'draft' ? 'checked' : '' }} class="mr-2" required>
                        <span class="text-sm text-gray-700">Brouillon</span>
                    </label>
                </div>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.ebooks.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    Créer l'e-book
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
