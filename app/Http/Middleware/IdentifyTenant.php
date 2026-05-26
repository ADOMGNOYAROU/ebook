<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // Ignorer les routes principales (landing, admin, etc.)
        $excludedRoutes = [
            'landing',
            'pricing',
            'about',
            'contact',
            'register',
            'login',
            'admin',
            'stripe.webhook',
        ];

        if (in_array($request->route()->getName(), $excludedRoutes)) {
            return $next($request);
        }

        // Identifier le tenant par sous-domaine ou domaine
        $hostname = $request->getHost();
        $subdomain = explode('.', $hostname)[0];

        // Si c'est le domaine principal, rediriger vers la landing
        if ($subdomain === config('app.domain') || $hostname === config('app.url')) {
            return redirect()->route('landing');
        }

        // Chercher le tenant
        $tenant = Tenant::where('subdomain', $subdomain)
            ->orWhere('domain', $hostname)
            ->active()
            ->first();

        if (!$tenant) {
            // Tenant non trouvé - page 404 ou redirection
            return redirect()->route('landing')->with('error', 'Ce tenant n\'existe pas.');
        }

        // Vérifier si le tenant est abonné
        if (!$tenant->isSubscribed() && !$tenant->isOnTrial()) {
            return redirect()->route('billing.checkout', $tenant->id);
        }

        // Stocker le tenant en session et dans le request
        session(['tenant_id' => $tenant->id]);
        $request->merge(['tenant' => $tenant]);

        // Configurer la connexion de base de données pour ce tenant
        $this->configureTenantDatabase($tenant);

        // Partager le tenant avec toutes les vues
        view()->share('tenant', $tenant);

        return $next($request);
    }

    private function configureTenantDatabase(Tenant $tenant): void
    {
        // Configuration pour l'isolation des données
        config([
            'database.connections.tenant.database' => 'tenant_' . $tenant->id,
        ]);

        // Forcer la reconnexion
        \DB::purge('tenant');
        \DB::reconnect('tenant');
    }
}
