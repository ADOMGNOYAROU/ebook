@extends('layouts.admin')

@section('title', 'E-books')
@section('subtitle', 'Gérez tous les e-books de la bibliothèque')

@section('content')
<div>
    <div class="mb-6 flex items-center justify-between">
        <div></div>
        <a href="{{ route('admin.ebooks.create') }}"
           class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-500/25 transition hover:from-amber-600 hover:to-orange-600">
            <i class="fas fa-plus text-xs"></i> Ajouter un e-book
        </a>
    </div>

    {{-- Filtres --}}
    <form action="{{ route('admin.ebooks.index') }}" method="GET"
          class="mb-5 flex flex-wrap gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Rechercher un e-book..."
               class="flex-1 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-800 placeholder-slate-400 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20 min-w-[180px]">
        <select name="category"
                class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 focus:border-amber-400 focus:outline-none focus:ring-2 focus:ring-amber-400/20">
            <option value="">Toutes les catégories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <button type="submit"
                class="rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
            <i class="fas fa-search mr-1.5"></i>Filtrer
        </button>
        <a href="{{ route('admin.ebooks.index') }}"
           class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
            Reset
        </a>
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50">
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Titre</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Auteur</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Catégorie</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Téléch.</th>
                        <th class="px-6 py-3.5 text-left text-xs font-black uppercase tracking-wider text-slate-500">Statut</th>
                        <th class="px-6 py-3.5 text-right text-xs font-black uppercase tracking-wider text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ebooks as $ebook)
                    <tr class="group transition hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($ebook->cover_image)
                                    <img class="h-10 w-8 flex-shrink-0 rounded-lg object-cover shadow" src="{{ asset('storage/' . $ebook->cover_image) }}" alt="">
                                @else
                                    <div class="flex h-10 w-8 flex-shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-400">
                                        <i class="fas fa-book text-xs"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-bold text-slate-800">{{ $ebook->title }}</p>
                                    <p class="text-xs text-slate-400">{{ $ebook->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">{{ $ebook->author }}</td>
                        <td class="px-6 py-4">
                            <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                {{ $ebook->category->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-slate-700">
                            <i class="fas fa-download mr-1 text-slate-300"></i>{{ $ebook->downloads_count }}
                        </td>
                        <td class="px-6 py-4">
                            @if($ebook->is_published)
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">Publié</span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700">Brouillon</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.ebooks.show', $ebook) }}"
                                   class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-indigo-50 hover:text-indigo-600">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.ebooks.edit', $ebook) }}"
                                   class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-amber-50 hover:text-amber-600">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.ebooks.destroy', $ebook) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cet e-book ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 text-slate-500 transition hover:bg-rose-50 hover:text-rose-600">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <i class="fas fa-book-open mb-3 block text-4xl text-slate-200"></i>
                            <p class="text-sm font-semibold text-slate-400">Aucun e-book trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ebooks->hasPages())
        <div class="flex items-center justify-between border-t border-slate-100 px-6 py-4">
            <p class="text-xs font-semibold text-slate-400">
                {{ $ebooks->firstItem() }}–{{ $ebooks->lastItem() }} sur {{ $ebooks->total() }}
            </p>
            <div class="flex gap-1">
                @if(!$ebooks->onFirstPage())
                    <a href="{{ $ebooks->previousPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-left text-xs"></i></a>
                @endif
                @if($ebooks->hasMorePages())
                    <a href="{{ $ebooks->nextPageUrl() }}" class="flex h-8 w-8 items-center justify-center rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-50"><i class="fas fa-chevron-right text-xs"></i></a>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
