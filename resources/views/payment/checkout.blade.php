@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-indigo-600 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Paiement sécurisé</h1>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Récapitulatif de votre achat</h2>
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    @if($ebook->cover_image)
                        <img src="{{ asset('storage/' . $ebook->cover_image) }}" alt="{{ $ebook->title }}" class="w-24 h-32 object-cover rounded">
                    @else
                        <div class="w-24 h-32 bg-gray-200 flex items-center justify-center text-gray-400 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-medium">{{ $ebook->title }}</h3>
                        <p class="text-gray-600">Auteur: {{ $ebook->author }}</p>
                        <p class="text-gray-900 font-bold text-xl mt-2">{{ number_format($ebook->price, 2, ',', ' ') }} €</p>
                    </div>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">Méthode de paiement</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <form id="payment-form" action="{{ route('process.payment', $ebook) }}" method="POST">
                        @csrf
                        
                        <!-- Sélecteur d'onglets pour les méthodes de paiement -->
                        <div class="mb-6 border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px" id="payment-tabs" data-tabs-toggle="#payment-tabs-content">
                                <li class="mr-2">
                                    <button type="button" 
                                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" 
                                            data-tabs-target="#card-payment" 
                                            data-tabs-active-classes="text-indigo-600 border-indigo-600"
                                            id="card-tab">
                                        <i class="fas fa-credit-card mr-2"></i>Carte bancaire
                                    </button>
                                </li>
                                <li class="mr-2">
                                    <button type="button" 
                                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" 
                                            data-tabs-target="#paypal-payment" 
                                            data-tabs-active-classes="text-indigo-600 border-indigo-600"
                                            id="paypal-tab">
                                        <i class="fab fa-paypal mr-2"></i>PayPal
                                    </button>
                                </li>
                                <li class="mr-2">
                                    <button type="button" 
                                            class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" 
                                            data-tabs-target="#mobile-money" 
                                            data-tabs-active-classes="text-indigo-600 border-indigo-600"
                                            id="mobile-tab">
                                        <i class="fas fa-mobile-alt mr-2"></i>Mobile Money
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Contenu des onglets -->
                        <div id="payment-tabs-content">
                            <!-- Paiement par carte -->
                            <div class="p-4 rounded-lg bg-gray-50" id="card-payment" role="tabpanel">
                                <div id="card-element" class="mb-4 p-3 border border-gray-300 rounded-lg">
                                    <!-- Elements will create form elements here -->
                                </div>
                                <div id="card-errors" role="alert" class="text-red-500 text-sm mb-4"></div>
                            </div>

                            <!-- Paiement par PayPal -->
                            <div class="p-4 rounded-lg bg-gray-50 hidden" id="paypal-payment" role="tabpanel">
                                <div class="text-center py-8">
                                    <div id="paypal-button-container" class="mb-4"></div>
                                    <p class="text-sm text-gray-500">Vous serez redirigé vers PayPal pour finaliser votre paiement</p>
                                </div>
                            </div>

                            <!-- Paiement par Mobile Money -->
                            <div class="p-4 rounded-lg bg-gray-50 hidden" id="mobile-money" role="tabpanel">
                                <div class="space-y-4">
                                    <div>
                                        <label for="mobile-provider" class="block text-sm font-medium text-gray-700 mb-1">Opérateur mobile</label>
                                        <select id="mobile-provider" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Sélectionnez votre opérateur</option>
                                            <option value="orange">Orange Money</option>
                                            <option value="mtn">MTN Mobile Money</option>
                                            <option value="moov">Moov Money</option>
                                            <option value="wave">Wave</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="mobile-number" class="block text-sm font-medium text-gray-700 mb-1">Numéro de téléphone</label>
                                        <input type="tel" id="mobile-number" placeholder="Ex: 771234567" 
                                               class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <button type="button" id="mobile-payment-button" 
                                            class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 transition duration-200">
                                        Payer avec Mobile Money
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button id="submit-button" class="w-full mt-6 bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 transition duration-200">
                            Payer {{ number_format($ebook->price, 2, ',', ' ') }} €
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center text-sm text-gray-500 mt-6">
                <p>Paiement sécurisé par</p>
                <div class="flex justify-center mt-2 space-x-4">
                    <img src="https://stripe.com/img/v3/homepage/checkout/logos/stripe-logo.svg" alt="Stripe" class="h-8">
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency=EUR"></script>
<script>
    // Gestion des onglets de paiement
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('[data-tabs-toggle] button');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            // Désactiver tous les onglets
            tabs.forEach(t => {
                t.classList.remove('text-indigo-600', 'border-indigo-600');
                t.classList.add('text-gray-500', 'hover:text-gray-600', 'hover:border-gray-300');
            });
            
            // Activer l'onglet cliqué
            this.classList.remove('text-gray-500', 'hover:text-gray-600', 'hover:border-gray-300');
            this.classList.add('text-indigo-600', 'border-indigo-600');
            
            // Masquer tous les contenus
            document.querySelectorAll('#payment-tabs-content > div').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Afficher le contenu correspondant
            const targetId = this.getAttribute('data-tabs-target').substring(1);
            document.getElementById(targetId).classList.remove('hidden');
            
            // Désactiver le bouton de soumission si nécessaire
            if (targetId === 'paypal-payment') {
                document.getElementById('submit-button').classList.add('hidden');
            } else {
                document.getElementById('submit-button').classList.remove('hidden');
            }
        });
    });
    
    // Activer l'onglet carte par défaut
    document.getElementById('card-tab').click();
});

// Configuration de PayPal
if (typeof paypal !== 'undefined') {
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '{{ $ebook->price }}',
                        currency_code: 'EUR'
                    },
                    description: 'Achat de {{ addslashes($ebook->title) }}',
                }],
                application_context: {
                    shipping_preference: 'NO_SHIPPING'
                }
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Soumettre le formulaire avec le token de paiement
                const form = document.getElementById('payment-form');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'payment_method';
                input.value = 'paypal';
                form.appendChild(input);
                
                const paymentId = document.createElement('input');
                paymentId.type = 'hidden';
                paymentId.name = 'payment_id';
                paymentId.value = details.id;
                form.appendChild(paymentId);
                
                form.submit();
            });
        },
        onError: function (err) {
            console.error('Erreur PayPal:', err);
            document.getElementById('card-errors').textContent = 'Une erreur est survenue avec PayPal. Veuillez réessayer ou choisir un autre moyen de paiement.';
        }
    }).render('#paypal-button-container');
}

// Gestion du paiement par Mobile Money
document.getElementById('mobile-payment-button')?.addEventListener('click', function() {
    const provider = document.getElementById('mobile-provider').value;
    const number = document.getElementById('mobile-number').value;
    
    if (!provider) {
        alert('Veuillez sélectionner un opérateur mobile');
        return;
    }
    
    if (!number || !/^[0-9]{9,15}$/.test(number)) {
        alert('Veuillez entrer un numéro de téléphone valide');
        return;
    }
    
    // Ici, vous devriez appeler votre API pour initier le paiement Mobile Money
    // Ceci est un exemple simplifié
    alert(`Paiement Mobile Money initié avec ${provider} pour le numéro ${number}`);
    
    // Soumettre le formulaire avec les détails du paiement
    const form = document.getElementById('payment-form');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'payment_method';
    input.value = 'mobile_money';
    form.appendChild(input);
    
    const providerInput = document.createElement('input');
    providerInput.type = 'hidden';
    providerInput.name = 'mobile_provider';
    providerInput.value = provider;
    form.appendChild(providerInput);
    
    form.submit();
});

// Configuration de Stripe
const stripe = Stripe('{{ config('cashier.key') }}');
const elements = stripe.elements();
const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    });
    
    cardElement.mount('#card-element');

    // Gestion des erreurs de la carte
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Gestion de la soumission du formulaire
    const form = document.getElementById('payment-form');
    if (form) {
        form.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.innerHTML = 'Traitement en cours...';
        
        // Désactiver le formulaire pour éviter les soumissions multiples
        const inputs = form.querySelectorAll('input, button');
        inputs.forEach(input => {
            input.disabled = true;
        });
        
        // Vérifier quelle méthode de paiement est sélectionnée
        const activeTab = document.querySelector('[data-tabs-target].text-indigo-600');
        
        if (activeTab && activeTab.id === 'card-tab') {
            // Paiement par carte
            const {error, paymentMethod} = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
            });

            if (error) {
                // Afficher l'erreur à l'utilisateur
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                
                // Réactiver le formulaire
                submitButton.disabled = false;
                submitButton.innerHTML = 'Payer {{ number_format($ebook->price, 2, ',', ' ') }} €';
                inputs.forEach(input => {
                    input.disabled = false;
                });
                return;
            }

            // Ajouter le PaymentMethod au formulaire
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'payment_method';
            hiddenInput.value = paymentMethod.id;
            form.appendChild(hiddenInput);
            
            // Soumettre le formulaire
            form.submit();
        } else {
            // Pour les autres méthodes de paiement, le formulaire est déjà géré
            // par leurs gestionnaires respectifs (PayPal, Mobile Money)
            // On ne fait rien ici pour éviter les soumissions multiples
        }
        });
    }
</script>
@endpush
@endsection
