<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Book Platform') }}</title>
        <meta name="description" content="Découvrez notre collection d'e-books gratuits et payants dans différentes catégories.">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('build/assets/app-B1GTiBvM.css') }}">
        <script src="{{ asset('build/assets/app-CiZ6hk-B.js') }}" defer></script>
        <style>
            .hero-banner {
                background-image: linear-gradient(rgba(37, 99, 235, 0.8), rgba(37, 99, 235, 0.8)), url('{{ asset('images/c3.jpg') }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;
                min-height: 500px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            .hero-content {
                position: relative;
                z-index: 10;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 m-0 p-0">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-600">
                                <i class="fas fa-book-open mr-2"></i>BookFlow
                            </a>
                        </div>
                        <!-- Navigation Links -->
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            <a href="{{ route('home') }}" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Accueil
                            </a>
                            <a href="#categories" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Catégories
                            </a>
                            <a href="#featured" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                                Nouveautés
                            </a>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md">Mon Compte</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="ml-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Déconnexion
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 px-3 py-2 text-sm font-medium">Connexion</a>
                            <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">S'inscrire</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="hero-banner text-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="hero-content">
                    <h1 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl mb-4">
                        Découvrez des milliers<br>d'e-books gratuits
                    </h1>
                    <p class="text-xl text-indigo-100 mb-8">
                        Téléchargez, lisez et partagez vos e-books préférés<br>en quelques clics. Une bibliothèque numérique à portée de main.
                    </p>
                    <div class="mt-6">
                        <div class="flex justify-center space-x-4">
                            <a href="#" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-md hover:bg-gray-100 transition-colors duration-200">
                                Explorer la bibliothèque
                            </a>
                            <a href="#featured" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 transition-colors duration-200">
                                E-books populaires
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Section -->
        <div id="featured" class="py-4 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Nouveautés</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Découvrez nos dernières sorties
                    </p>
                </div>

                <div class="mt-6">
                    <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                        <!-- Book Card 1 -->
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-2 aspect-h-3 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                <img src="https://via.placeholder.com/300x450" alt="Livre 1" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="#">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            Le Guide du Développeur Web
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">Développement</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">19.99€</p>
                            </div>
                        </div>

                        <!-- Book Card 2 -->
                        <div class="group relative">
                            <div class="w-full min-h-80 bg-gray-200 aspect-w-2 aspect-h-3 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                                <img src="https://via.placeholder.com/300x450" alt="Livre 2" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="#">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            L'Art de la Programmation
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">Informatique</p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">24.99€</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Voir tous les livres
                    </a>
                </div>
            </div>
        </div>

        <!-- Categories Section -->
        <div id="categories" class="bg-gray-50 py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Catégories</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Parcourir par catégorie
                    </p>
                </div>

                <div class="mt-6">
                    <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
                        @for($i = 1; $i <= 6; $i++)
                        <a href="#" class="col-span-1 flex flex-col text-center bg-white rounded-lg shadow divide-y divide-gray-200 hover:shadow-lg transition-shadow duration-200">
                            <div class="flex-1 flex flex-col p-8">
                                <i class="fas fa-book text-4xl text-indigo-600 mx-auto"></i>
                                <h3 class="mt-6 text-gray-900 text-sm font-medium">Catégorie {{ $i }}</h3>
                                <dl class="mt-1 flex-grow flex flex-col justify-between">
                                    <dd class="text-gray-500 text-sm">25 livres</dd>
                                </dl>
                            </div>
                        </a>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-indigo-700">
            <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Prêt à commencer ?</span>
                    <span class="block">Inscrivez-vous dès maintenant.</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-indigo-200">
                    Accédez à des milliers d'e-books dans tous les domaines, disponibles immédiatement après inscription.
                </p>
                <a href="{{ route('register') }}" class="mt-8 w-full inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 sm:w-auto">
                    S'inscrire gratuitement
                </a>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white">
            <div class="max-w-7xl mx-auto py-12 px-4 overflow-hidden sm:px-6 lg:px-8">
                <nav class="-mx-5 -my-2 flex flex-wrap justify-center" aria-label="Footer">
                    <div class="px-5 py-2">
                        <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                            À propos
                        </a>
                    </div>
                    <div class="px-5 py-2">
                        <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                            Contact
                        </a>
                    </div>
                    <div class="px-5 py-2">
                        <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                            Conditions d'utilisation
                        </a>
                    </div>
                    <div class="px-5 py-2">
                        <a href="#" class="text-base text-gray-500 hover:text-gray-900">
                            Politique de confidentialité
                        </a>
                    </div>
                </nav>
                <div class="mt-8 flex justify-center space-x-6">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Facebook</span>
                        <i class="fab fa-facebook h-6 w-6"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Twitter</span>
                        <i class="fab fa-twitter h-6 w-6"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="sr-only">Instagram</span>
                        <i class="fab fa-instagram h-6 w-6"></i>
                    </a>
                </div>
                <p class="mt-8 text-center text-base text-gray-400">
                    &copy; {{ date('Y') }} BookFlow. Tous droits réservés.
                </p>
            </div>
        </footer>

        <!-- Scripts -->
        @stack('scripts')
    </body>
</html>