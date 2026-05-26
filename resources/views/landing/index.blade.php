<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Book Platform SaaS - Votre Librairie Digitale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-2xl font-bold text-gradient">E-Book SaaS</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-700 hover:text-gray-900">Fonctionnalités</a>
                    <a href="#pricing" class="text-gray-700 hover:text-gray-900">Tarifs</a>
                    <a href="#about" class="text-gray-700 hover:text-gray-900">À propos</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-gray-900">Connexion</a>
                    <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        Commencer Gratuitement
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Votre Librairie<br>
                    <span class="text-yellow-300">Digitale Multi-Tenants</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-gray-100">
                    Lancez votre plateforme d'e-books en quelques minutes.<br>
                    Auteurs, éditeurs, formateurs : mettez votre contenu en valeur.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-rocket mr-2"></i>
                        Essai Gratuit 14 Jours
                    </a>
                    <a href="#pricing" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                        Voir les Tarifs
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Fonctionnalités Puissantes
                </h2>
                <p class="text-xl text-gray-600">
                    Tout ce dont vous avez besoin pour gérer votre librairie digitale
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-upload text-indigo-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Upload Simple</h3>
                    <p class="text-gray-600">
                        Importez vos e-books PDF en un clic. Génération automatique des aperçus et métadonnées.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-book-reader text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Lecteur Intégré</h3>
                    <p class="text-gray-600">
                        Lecteur PDF moderne avec annotations, signets et mode plein écran pour vos lecteurs.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Analytics Avancées</h3>
                    <p class="text-gray-600">
                        Suivez les lectures, téléchargements et engagement de votre audience en temps réel.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-yellow-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-palette text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Branding Personnalisé</h3>
                    <p class="text-gray-600">
                        Personnalisez les couleurs, logo et domaine pour refléter votre marque.
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Applications Mobiles</h3>
                    <p class="text-gray-600">
                        Proposez vos applications mobiles white-label (plans Enterprise).
                    </p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-6">
                        <i class="fas fa-code text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">API REST</h3>
                    <p class="text-gray-600">
                        Intégrez votre librairie avec vos outils existants via notre API complète.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Tarifs Simples et Transparents
                </h2>
                <p class="text-xl text-gray-600">
                    Choisissez le plan qui correspond à vos besoins
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Starter Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">STARTER</h3>
                        <p class="text-gray-600 mb-4">Parfait pour démarrer</p>
                        <div class="text-4xl font-bold text-gray-900">
                            0€<span class="text-lg text-gray-600">/mois</span>
                        </div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>10 e-books maximum</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>100 Mo stockage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>1 utilisateur</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Lecteur PDF basic</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-times text-gray-300 mr-3"></i>
                            <span class="text-gray-400">Domaine perso</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-times text-gray-300 mr-3"></i>
                            <span class="text-gray-400">API access</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold text-center block hover:bg-gray-300">
                        Commencer Gratuitement
                    </a>
                </div>

                <!-- Pro Plan -->
                <div class="bg-indigo-600 text-white rounded-2xl shadow-xl p-8 transform scale-105">
                    <div class="text-center mb-8">
                        <div class="bg-yellow-400 text-indigo-900 text-sm font-semibold px-3 py-1 rounded-full inline-block mb-4">
                            LE PLUS POPULAIRE
                        </div>
                        <h3 class="text-2xl font-bold mb-2">PRO</h3>
                        <p class="text-indigo-100 mb-4">Idéal pour les créateurs</p>
                        <div class="text-4xl font-bold">
                            29€<span class="text-lg text-indigo-100">/mois</span>
                        </div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>100 e-books</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>5 Go stockage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>5 utilisateurs</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>Lecteur PDF avancé</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>Domaine perso</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-yellow-400 mr-3"></i>
                            <span>API access</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full bg-white text-indigo-600 py-3 rounded-lg font-semibold text-center block hover:bg-gray-100">
                        Essayer Gratuitement
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold mb-2">ENTERPRISE</h3>
                        <p class="text-gray-600 mb-4">Pour les organisations</p>
                        <div class="text-4xl font-bold text-gray-900">
                            99€<span class="text-lg text-gray-600">/mois</span>
                        </div>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>E-books illimités</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>50 Go stockage</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Utilisateurs illimités</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Lecteur PDF premium</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Apps mobiles</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-3"></i>
                            <span>Support prioritaire</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold text-center block hover:bg-gray-800">
                        Contacter les Ventes
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">
                Prêt à Lancer Votre Librairie ?
            </h2>
            <p class="text-xl mb-8 text-gray-100">
                Rejoignez des centaines d'auteurs et éditeurs qui utilisent notre plateforme
            </p>
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition inline-block">
                <i class="fas fa-rocket mr-2"></i>
                Commencer Votre Essai Gratuit
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 text-gradient">E-Book SaaS</h3>
                    <p class="text-gray-400">
                        La plateforme multi-tenants pour votre librairie digitale.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Produit</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white">Fonctionnalités</a></li>
                        <li><a href="#pricing" class="hover:text-white">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white">API</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">Centre d'aide</a></li>
                        <li><a href="#" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Légal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">CGU</a></li>
                        <li><a href="#" class="hover:text-white">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-white">Mentions légales</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2026 E-Book SaaS. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
