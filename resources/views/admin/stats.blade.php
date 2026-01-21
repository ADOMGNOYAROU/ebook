@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistiques</h1>
            <p class="text-gray-600">Vue d'ensemble des activités de la plateforme</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour au tableau de bord
        </a>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Téléchargements par mois -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Téléchargements par mois</h2>
            <canvas id="downloadsChart" height="300"></canvas>
        </div>

        <!-- Inscriptions par mois -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Inscriptions par mois</h2>
            <canvas id="usersChart" height="300"></canvas>
        </div>
    </div>

    <!-- Ebooks les plus téléchargés -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Ebooks les plus téléchargés</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auteur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléchargements</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topEbooks as $ebook)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ebook->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">{{ $ebook->author }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    {{ $ebook->category->name ?? 'Non catégorisé' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $ebook->downloads_count }} téléchargement{{ $ebook->downloads_count > 1 ? 's' : '' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                Aucun téléchargement pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données pour les graphiques
    const downloadsData = @json([
        'labels' => $downloadsByMonth->pluck('month'),
        'data' => $downloadsByMonth->pluck('count')
    ]);

    const usersData = @json([
        'labels' => $usersByMonth->pluck('month'),
        'data' => $usersByMonth->pluck('count')
    ]);

    // Configuration commune des graphiques
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    };

    // Graphique des téléchargements
    new Chart(document.getElementById('downloadsChart'), {
        type: 'bar',
        data: {
            labels: downloadsData.labels,
            datasets: [{
                label: 'Téléchargements',
                data: downloadsData.data,
                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                borderColor: 'rgba(79, 70, 229, 1)',
                borderWidth: 1
            }]
        },
        options: chartOptions
    });

    // Graphique des inscriptions
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: usersData.labels,
            datasets: [{
                label: 'Inscriptions',
                data: usersData.data,
                fill: false,
                borderColor: 'rgba(16, 185, 129, 1)',
                tension: 0.3
            }]
        },
        options: chartOptions
    });
</script>
@endpush
@endsection
