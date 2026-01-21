@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-2xl mx-auto text-center bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-yellow-500 px-6 py-8">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Paiement annulé</h1>
            <p class="text-yellow-100 mt-2">Votre paiement n'a pas abouti</p>
        </div>
        
        <div class="p-8">
            <div class="mb-8">
                <p class="text-gray-700 mb-6">
                    Votre paiement a été annulé. Aucun montant n'a été débité de votre compte.
                </p>
                <p class="text-gray-600">
                    Si vous avez des questions concernant votre commande, n'hésitez pas à contacter notre service client.
                </p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('public.ebooks.index') }}" class="inline-block w-full md:w-auto bg-indigo-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                    Retour à la bibliothèque
                </a>
                <a href="{{ route('contact') }}" class="inline-block w-full md:w-auto bg-white text-gray-700 border border-gray-300 py-3 px-6 rounded-lg font-medium hover:bg-gray-50 transition duration-200">
                    Contacter le support
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
