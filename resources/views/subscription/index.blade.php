@extends('layouts.dashboard')

@section('title', 'Abonnement')
@section('subtitle', 'Choisissez votre plan')

@section('content')
<section class="px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{-- Header --}}
        <div class="mb-12 text-center">
            <span class="section-eyebrow">Abonnement</span>
            <h1 class="section-title">Choisissez votre plan</h1>
            <p class="section-subtitle mx-auto">Accédez à tous nos e-books sans limites</p>
        </div>

        {{-- Plans --}}
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-2">
            {{-- Plan Gratuit --}}
            <div class="relative rounded-3xl border-2 border-slate-200 bg-white p-8 shadow-sm">
                <div class="mb-6">
                    <h2 class="text-2xl font-black text-slate-900">Gratuit</h2>
                    <p class="mt-2 text-slate-500">Parfait pour découvrir</p>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-black text-slate-900">0 €</span>
                    <span class="text-slate-500">/mois</span>
                </div>

                <ul class="mb-8 space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">{{ $user->downloads_remaining }} téléchargements gratuits</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Accès aux livres gratuits</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Favoris illimités</span>
                    </li>
                    <li class="flex items-start gap-3 opacity-40">
                        <i class="fas fa-times text-slate-400 mt-1"></i>
                        <span class="text-slate-400">Téléchargements illimités</span>
                    </li>
                    <li class="flex items-start gap-3 opacity-40">
                        <i class="fas fa-times text-slate-400 mt-1"></i>
                        <span class="text-slate-400">Accès prioritaire</span>
                    </li>
                </ul>

                <div class="rounded-xl bg-slate-100 py-3 text-center text-sm font-semibold text-slate-600">
                    @if($user->isFree())Votre plan actuel@endif
                </div>
            </div>

            {{-- Plan Premium --}}
            <div class="relative rounded-3xl border-2 border-indigo-500 bg-gradient-to-br from-indigo-50 to-violet-50 p-8 shadow-xl shadow-indigo-500/10">
                <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                    <span class="rounded-full bg-indigo-600 px-4 py-1 text-xs font-bold text-white">POPULAIRE</span>
                </div>

                <div class="mb-6">
                    <h2 class="text-2xl font-black text-indigo-600">Premium</h2>
                    <p class="mt-2 text-slate-500">Pour les lecteurs passionnés</p>
                </div>

                <div class="mb-6">
                    <span class="text-4xl font-black text-indigo-600">9.99 €</span>
                    <span class="text-slate-500">/mois</span>
                    <p class="mt-1 text-xs text-slate-400">ou 99.99 €/an (économisez 17%)</p>
                </div>

                <ul class="mb-8 space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Téléchargements illimités</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Accès à tous les e-books</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Favoris illimités</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Accès prioritaire</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-emerald-500 mt-1"></i>
                        <span class="text-slate-600">Support prioritaire</span>
                    </li>
                </ul>

                @if($user->isPremium())
                <div class="rounded-xl bg-indigo-100 py-3 text-center text-sm font-semibold text-indigo-600">
                    Plan actuel - Expire le {{ $user->subscription_ends_at ? $user->subscription_ends_at->format('d/m/Y') : 'jamais' }}
                </div>
                @else
                <form action="{{ route('user.subscription.subscribe') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="plan" value="monthly">

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Numéro de téléphone</label>
                        <input type="tel" name="phone" placeholder="Ex: 90123456" required
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-900 mb-2">Réseau</label>
                        <select name="network" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-indigo-500 focus:outline-none">
                            <option value="">Sélectionnez</option>
                            <option value="FLOOZ">Flooz</option>
                            <option value="TMONEY">T-Money</option>
                            <option value="MOOV">Moov</option>
                            <option value="TOGOCEL">TogoCel</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 text-base">
                        <i class="fas fa-crown mr-2"></i>Devenir Premium
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Comparaison --}}
        <div class="mt-16 rounded-2xl border border-slate-200 bg-white p-8">
            <h3 class="text-center text-xl font-black text-slate-900 mb-8">Comparaison des plans</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="py-4 text-left font-bold text-slate-900">Fonctionnalité</th>
                            <th class="py-4 text-center font-bold text-slate-900">Gratuit</th>
                            <th class="py-4 text-center font-bold text-indigo-600">Premium</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-slate-100">
                            <td class="py-4 text-slate-600">Téléchargements</td>
                            <td class="py-4 text-center text-slate-600">{{ $user->downloads_remaining }}</td>
                            <td class="py-4 text-center font-bold text-emerald-500">Illimités</td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-4 text-slate-600">Accès aux livres payants</td>
                            <td class="py-4 text-center text-slate-400">X</td>
                            <td class="py-4 text-center"><i class="fas fa-check text-emerald-500"></i></td>
                        </tr>
                        <tr class="border-b border-slate-100">
                            <td class="py-4 text-slate-600">Favoris</td>
                            <td class="py-4 text-center"><i class="fas fa-check text-emerald-500"></i></td>
                            <td class="py-4 text-center"><i class="fas fa-check text-emerald-500"></i></td>
                        </tr>
                        <tr>
                            <td class="py-4 text-slate-600">Support</td>
                            <td class="py-4 text-center text-slate-400">Standard</td>
                            <td class="py-4 text-center font-bold text-emerald-500">Prioritaire</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
