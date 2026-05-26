@extends('layouts.app')

@section('title', 'Dashboard SaaS')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 mb-0">Dashboard SaaS</h1>
            <p class="text-muted">Vue d'ensemble de votre plateforme multi-tenants</p>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Tenants
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ App\Models\Tenant::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                MRR (Revenue Mensuel)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                €{{ number_format(App\Models\Subscription::active()->sum('amount'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-euro-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Abonnements Actifs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ App\Models\Subscription::active()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Essais en Cours
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ App\Models\Tenant::where('trial_ends_at', '>', now())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Évolution des Abonnements</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="subscriptionsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Répartition des Plans</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="plansChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Starter
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Pro
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-info"></i> Enterprise
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tenants & Subscriptions -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tenants Récents</h6>
                </div>
                <div class="card-body">
                    @php
                        $recentTenants = App\Models\Tenant::with('subscription.plan')->latest()->take(5)->get();
                    @endphp
                    @if($recentTenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Plan</th>
                                        <th>Statut</th>
                                        <th>Créé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTenants as $tenant)
                                        <tr>
                                            <td>{{ $tenant->name }}</td>
                                            <td>
                                                @if($tenant->subscription)
                                                    <span class="badge badge-{{ $tenant->subscription->plan->slug === 'starter' ? 'secondary' : ($tenant->subscription->plan->slug === 'pro' ? 'success' : 'info') }}">
                                                        {{ $tenant->subscription->plan->name }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-light">Aucun</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($tenant->subscription)
                                                    <span class="badge badge-{{ $tenant->subscription->isActive() ? 'success' : 'warning' }}">
                                                        {{ $tenant->subscription->formatted_status }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>{{ $tenant->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun tenant enregistré.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Abonnements Expirant Bientôt</h6>
                </div>
                <div class="card-body">
                    @php
                        $expiringSoon = App\Models\Subscription::expiringSoon()->with('tenant')->get();
                    @endphp
                    @if($expiringSoon->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tenant</th>
                                        <th>Plan</th>
                                        <th>Expiration</th>
                                        <th>Jours restants</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringSoon as $subscription)
                                        <tr>
                                            <td>{{ $subscription->tenant->name }}</td>
                                            <td>{{ $subscription->plan->name }}</td>
                                            <td>{{ $subscription->current_period_end->format('d/m/Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $subscription->getDaysUntilEnd() <= 3 ? 'danger' : 'warning' }}">
                                                    {{ $subscription->getDaysUntilEnd() }} jours
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun abonnement n'expire dans les 7 prochains jours.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart.js - Subscriptions Evolution
    const ctx1 = document.getElementById('subscriptionsChart').getContext('2d');
    const subscriptionsChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun'],
            datasets: [{
                label: 'Nouveaux abonnements',
                data: [12, 19, 15, 25, 22, 30],
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Abonnements actifs',
                data: [65, 72, 78, 85, 89, 95],
                borderColor: 'rgb(153, 102, 255)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Chart.js - Plans Distribution
    const ctx2 = document.getElementById('plansChart').getContext('2d');
    const plansChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Starter', 'Pro', 'Enterprise'],
            datasets: [{
                data: [
                    {{ App\Models\Subscription::whereHas('plan', fn($q) => $q->where('slug', 'starter'))->count() }},
                    {{ App\Models\Subscription::whereHas('plan', fn($q) => $q->where('slug', 'pro'))->count() }},
                    {{ App\Models\Subscription::whereHas('plan', fn($q) => $q->where('slug', 'enterprise'))->count() }}
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>
@endpush
