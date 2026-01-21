@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-500 px-6 py-8">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Paiement réussi !</h1>
            <p class="text-green-100 mt-2">Merci pour votre achat</p>
        </div>
        
        <div class="p-8">
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Récapitulatif de votre commande</h2>
                <div class="bg-gray-50 p-6 rounded-lg text-left">
                    <div class="flex items-center space-x-4 mb-4">
                        @if($ebook->cover_image)
                            <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-16 h-20 object-cover rounded">
                        @else
                            <div class="w-16 h-20 bg-gray-200 flex items-center justify-center text-gray-400 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        @endif
                        <div>
                            <h3 class="font-medium">{{ $ebook->title }}</h3>
                            <p class="text-sm text-gray-600">Auteur: {{ $ebook->author }}</p>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between mb-2">
                            <span>Montant payé :</span>
                            <span class="font-medium">{{ number_format($amount, 2, ',', ' ') }} €</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span>Référence de paiement :</span>
                            <span class="font-mono text-sm">{{ substr($payment_id, 0, 8) }}...</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Date :</span>
                            <span>{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <a href="{{ route('public.ebooks.show', $ebook->slug) }}" class="inline-block w-full md:w-auto bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                    Accéder à votre ebook
                </a>
                <a href="{{ route('public.ebooks.index') }}" class="inline-block w-full md:w-auto bg-white text-gray-700 border border-gray-300 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition duration-200">
                    Retour à la bibliothèque
                </a>
            </div>

            <div class="mt-8 text-sm text-gray-500">
                <p>Un email de confirmation a été envoyé à <span class="font-medium">{{ auth()->user()->email }}</span></p>
                <p class="mt-2">Pour toute question, contactez notre <a href="{{ route('contact') }}" class="text-indigo-600 hover:underline">service client</a>.</p>
            </div>
        </div>
    </div>
</div>
@endsection
