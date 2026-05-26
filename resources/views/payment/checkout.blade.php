@extends('layouts.app')

@section('content')
<section class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="section-title">Paiement sécurisé</h1>
            <p class="section-subtitle">Finalisez votre achat en toute sécurité</p>
        </div>

        <div class="grid gap-8 lg:grid-cols-3">
            {{-- Récapitulatif --}}
            <div class="lg:col-span-1">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 mb-4">Récapitulatif</h2>
                    
                    @if($ebook->cover_path)
                        <img src="{{ Storage::url($ebook->cover_path) }}" alt="{{ $ebook->title }}" 
                             class="mb-4 h-40 w-full rounded-xl object-cover">
                    @else
                        <div class="mb-4 flex h-40 w-full items-center justify-center rounded-xl bg-slate-100">
                            <i class="fas fa-book text-4xl text-slate-400"></i>
                        </div>
                    @endif
                    
                    <h3 class="text-base font-bold text-slate-900">{{ $ebook->title }}</h3>
                    <p class="text-sm text-slate-400">{{ $ebook->author }}</p>
                    
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Prix unitaire</span>
                            <span class="font-semibold text-slate-900">{{ number_format($ebook->price ?? 9.99, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="mt-2 flex justify-between text-base font-black">
                            <span class="text-slate-900">Total</span>
                            <span class="text-indigo-600">{{ number_format($ebook->price ?? 9.99, 2, ',', ' ') }} €</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Formulaire de paiement --}}
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-black text-slate-900 mb-6">Méthode de paiement</h2>
                    
                    <form action="{{ route('process.payment', $ebook->id) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        @if($errors->any())
                        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4">
                            <p class="text-sm font-bold text-rose-800"><i class="fas fa-exclamation-circle mr-2"></i>Erreurs :</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5 text-xs text-rose-700">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Numéro de téléphone --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Numéro de téléphone</label>
                            <input type="tel" name="phone" placeholder="Ex: 90123456" required
                                   class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                            <p class="mt-1 text-xs text-slate-400">Ce numéro sera utilisé pour le paiement Mobile Money</p>
                        </div>

                        {{-- Réseau --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-900 mb-2">Réseau Mobile Money</label>
                            <select name="network" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                                <option value="">Sélectionnez votre réseau</option>
                                <option value="FLOOZ">Flooz</option>
                                <option value="TMONEY">T-Money</option>
                                <option value="MOOV">Moov</option>
                                <option value="TOGOCEL">TogoCel</option>
                            </select>
                        </div>

                        <div class="rounded-xl border-2 border-dashed border-slate-200 p-6 text-center">
                            <i class="fas fa-mobile-alt text-4xl text-slate-400 mb-3"></i>
                            <p class="text-sm text-slate-600">Vous serez redirigé vers PayGateGlobal pour finaliser votre paiement</p>
                            <p class="mt-2 text-xs text-slate-400">Paiement sécurisé via {{ request('network') ?? 'Mobile Money' }}</p>
                        </div>

                        <button type="submit" class="btn-primary w-full py-4 text-base">
                            <i class="fas fa-lock mr-2"></i>Payer {{ number_format($ebook->price ?? 9.99, 2, ',', ' ') }} €
                        </button>

                        <p class="text-center text-xs text-slate-400">
                            <i class="fas fa-shield-alt mr-1"></i>Paiement sécurisé par PayGateGlobal
                        </p>
                    </form>
                </div>

                {{-- Sécurité --}}
                <div class="mt-6 flex items-center justify-center gap-4 text-slate-400">
                    <i class="fas fa-lock text-lg"></i>
                    <span class="text-xs">Transaction 100% sécurisée</span>
                    <span class="text-slate-200">|</span>
                    <span class="text-xs">Données chiffrées</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
