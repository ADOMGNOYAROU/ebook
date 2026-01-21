<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Vérifie si l'utilisateur connecté est un administrateur.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifie si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifie si l'utilisateur a le rôle admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/')
                ->with('error', 'Accès refusé. Vous devez être administrateur pour accéder à cette page.');
        }

        // Si tout est bon, on laisse passer la requête
        return $next($request);
    }
}