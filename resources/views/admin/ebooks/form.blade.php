@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ isset($ebook) ? 'Modifier l\'e-book' : 'Ajouter un nouvel e-book' }}
                </h2>
            </div>
            
            <form action="{{ isset($ebook) ? route('admin.ebooks.update', $ebook) : route('admin.ebooks.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="p-6">
                @csrf
                @if(isset($ebook))
                    @method('PUT')
                @endif

                <!-- Section Informations de base -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de base</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Titre -->
                        <div class="col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" 
                                   value="{{ old('title', $ebook->title ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auteur -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700">Auteur <span class="text-red-500">*</span></label>
                            <input type="text" name="author" id="author" 
                                   value="{{ old('author', $ebook->author ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            @error('author')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Catégorie <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ (old('category_id', $ebook->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- ISBN -->
                        <div>
                            <label for="isbn" class="block text-sm font-medium text-gray-700">ISBN</label>
                            <input type="text" name="isbn" id="isbn" 
                                   value="{{ old('isbn', $ebook->isbn ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('isbn')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de publication -->
                        <div>
                            <label for="published_date" class="block text-sm font-medium text-gray-700">Date de publication</label>
                            <input type="date" name="published_date" id="published_date" 
                                   value="{{ old('published_date', isset($ebook->published_date) ? $ebook->published_date->format('Y-m-d') : '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('published_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nombre de pages -->
                        <div>
                            <label for="page_count" class="block text-sm font-medium text-gray-700">Nombre de pages</label>
                            <input type="number" name="page_count" id="page_count" min="1"
                                   value="{{ old('page_count', $ebook->page_count ?? '') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('page_count')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Langue -->
                        <div>
                            <label for="language" class="block text-sm font-medium text-gray-700">Langue</label>
                            <input type="text" name="language" id="language" 
                                   value="{{ old('language', $ebook->language ?? 'Français') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('language')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut de publication -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut</label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="is_published_1" name="is_published" type="radio" value="1" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_published', isset($ebook) && $ebook->is_published ? 'checked' : '') ? 'checked' : '' }}>
                                    <label for="is_published_1" class="ml-2 block text-sm text-gray-700">
                                        Publié
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="is_published_0" name="is_published" type="radio" value="0" 
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                           {{ !old('is_published', isset($ebook) && $ebook->is_published) ? 'checked' : '' }}>
                                    <label for="is_published_0" class="ml-2 block text-sm text-gray-700">
                                        Brouillon
                                    </label>
                                </div>
                            </div>
                            @error('is_published')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type <span class="text-red-500">*</span></label>
                            <div class="mt-2 space-y-2">
                                <div class="flex items-center">
                                    <input id="is_free_1" name="is_free" type="radio" value="1"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_free', isset($ebook) ? (int) $ebook->is_free : 1) == 1 ? 'checked' : '' }} required>
                                    <label for="is_free_1" class="ml-2 block text-sm text-gray-700">Gratuit</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="is_free_0" name="is_free" type="radio" value="0"
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500"
                                           {{ old('is_free', isset($ebook) ? (int) $ebook->is_free : 1) == 0 ? 'checked' : '' }} required>
                                    <label for="is_free_0" class="ml-2 block text-sm text-gray-700">Payant</label>
                                </div>
                            </div>
                            @error('is_free')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Fichiers -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Fichiers</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Couverture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image de couverture</label>
                            <div class="mt-1 flex items-center">
                                <span class="inline-block h-12 w-12 rounded-md overflow-hidden bg-gray-100">
                                    @if(isset($ebook) && $ebook->cover_image)
                                        <img id="cover-preview" src="{{ asset('storage/' . $ebook->cover_image) }}" alt="Couverture" class="h-full w-full object-cover">
                                    @else
                                        <div id="cover-placeholder" class="h-full w-full flex items-center justify-center text-gray-400">
                                            <i class="fas fa-image text-2xl"></i>
                                        </div>
                                    @endif
                                </span>
                                <label for="cover_image" class="ml-5">
                                    <div class="py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 cursor-pointer">
                                        Changer
                                    </div>
                                    <input id="cover_image" name="cover_image" type="file" class="sr-only" onchange="previewCover(this)">
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG (max. 2MB)</p>
                            @error('cover_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Fichier PDF -->
                        <div>
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700">Fichier PDF <span class="text-red-500">*</span></label>
                            <div class="mt-1">
                                @if(isset($ebook) && $ebook->file_path)
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 text-xl mr-2"></i>
                                        <span class="text-sm text-gray-700 truncate">{{ basename($ebook->file_path) }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Fichier actuel</p>
                                @endif
                                <input id="pdf_file" name="pdf_file" type="file" accept=".pdf" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                       {{ !isset($ebook) ? 'required' : '' }}>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Format PDF uniquement (max. 10MB)</p>
                            @error('pdf_file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section Description -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Résumé <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <textarea id="description" name="description" rows="4" 
                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"
                                      required>{{ old('description', $ebook->description ?? '') }}</textarea>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Décrivez brièvement le contenu de l'e-book.</p>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Section Mots-clés -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mots-clés</h3>
                    <div>
                        <label for="keywords" class="block text-sm font-medium text-gray-700">Mots-clés</label>
                        <input type="text" name="keywords" id="keywords" 
                               value="{{ old('keywords', isset($ebook) && $ebook->keywords ? implode(', ', json_decode($ebook->keywords, true)) : '') }}" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               placeholder="séparés par des virgules">
                        <p class="mt-1 text-xs text-gray-500">Séparez les mots-clés par des virgules (ex: roman, aventure, fantastique)</p>
                        @error('keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Boutons de soumission -->
                <div class="flex justify-end pt-5 border-t border-gray-200">
                    <a href="{{ route('admin.ebooks.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Annuler
                    </a>
                    <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ isset($ebook) ? 'Mettre à jour' : 'Créer' }} l'e-book
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Aperçu de l'image de couverture
    function previewCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                let preview = document.getElementById('cover-preview');
                const placeholder = document.getElementById('cover-placeholder');
                
                if (!preview) {
                    preview = document.createElement('img');
                    preview.id = 'cover-preview';
                    preview.className = 'h-full w-full object-cover';
                    const parent = document.querySelector('.inline-block.h-12.w-12.rounded-md.overflow-hidden.bg-gray-100');
                    if (placeholder) {
                        parent.removeChild(placeholder);
                    }
                    parent.appendChild(preview);
                }
                
                preview.src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Initialisation des tooltips (si nécessaire)
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips avec Tippy.js ou autre bibliothèque si nécessaire
    });
</script>
@endpush

@endsection
