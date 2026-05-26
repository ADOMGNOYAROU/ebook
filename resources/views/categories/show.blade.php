@extends('layouts.app')

@section('content')
<section class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('public.ebooks.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition mb-4">
                <i class="fas fa-arrow-left"></i> Retour au catalogue
            </a>
            <h1 class="section-title">{{ $category->name }}</h1>
            <p class="section-subtitle">{{ $category->ebooks_count }} livre{{ $category->ebooks_count != 1 ? 's' : '' }} dans cette catégorie</p>
        </div>

        {{-- Grille d'ebooks --}}
        @if($ebooks->count() > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($ebooks as $ebook)
                    <a href="{{ route('public.ebooks.show', $ebook) }}" class="ebook-card group">
                        {{-- Cover --}}
                        <div class="relative mb-4 overflow-hidden rounded-2xl bg-slate-100">
                            @if($ebook->cover_path)
                                <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}"
                                     class="h-56 w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-56 w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                </div>
                            @endif
                            {{-- Badge gratuit --}}
                            @if($ebook->is_free)
                                <span class="absolute top-3 left-3 rounded-full bg-emerald-500 px-2.5 py-1 text-xs font-bold text-white shadow-lg">
                                    Gratuit
                                </span>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div>
                            <h3 class="text-base font-black text-slate-900 line-clamp-2 group-hover:text-indigo-600 transition">
                                {{ $ebook->title }}
                            </h3>
                            <p class="mt-1 text-sm font-semibold text-slate-400">{{ $ebook->author }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wide text-slate-500">
                                    {{ $ebook->category->name }}
                                </span>
                                <span class="flex items-center gap-1 text-xs font-semibold text-slate-400">
                                    <i class="fas fa-download"></i> {{ number_format($ebook->downloads_count) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12 flex justify-center">
                {{ $ebooks->links() }}
            </div>
        @else
            <div class="rounded-2xl border-2 border-dashed border-slate-200 py-20 text-center">
                <i class="fas fa-book mb-4 text-4xl text-slate-300"></i>
                <h3 class="text-lg font-bold text-slate-800">Aucun e-book dans cette catégorie</h3>
                <p class="mt-2 text-slate-400">Revenez plus tard pour découvrir de nouveaux livres</p>
                <a href="{{ route('public.ebooks.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-arrow-left"></i> Retour au catalogue
                </a>
            </div>
        @endif
    </div>
</section>

@push('styles')
<style>
.ebook-card {
    @apply block rounded-2xl border border-slate-200 bg-white p-4 transition-shadow hover:shadow-xl hover:shadow-indigo-500/10;
}
</style>
@endpush
@endsection
