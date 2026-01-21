<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Récupérer les 6 ebooks les plus téléchargés
        $mostDownloaded = Ebook::with('category')
            ->orderBy('downloads_count', 'desc')
            ->take(6)
            ->get();

        // Récupérer les 8 derniers ebooks ajoutés
        $latestEbooks = Ebook::with('category')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Récupérer uniquement les catégories qui ont des ebooks
        $categories = Category::select('categories.*', DB::raw('COUNT(ebooks.id) as ebooks_count'))
            ->leftJoin('ebooks', 'categories.id', '=', 'ebooks.category_id')
            ->groupBy([
                'categories.id', 
                'categories.name', 
                'categories.slug', 
                'categories.icon', 
                'categories.created_at', 
                'categories.updated_at'
            ])
            ->orderBy('categories.name')
            ->get();

        // Ajouter des logs de débogage
        \Log::info('Données du contrôleur HomeController:');
        \Log::info('Nombre d\'e-books populaires: ' . $mostDownloaded->count());
        \Log::info('Nombre d\'e-books récents: ' . $latestEbooks->count());
        \Log::info('Nombre de catégories: ' . $categories->count());

        return view('home', [
            'popularEbooks' => $mostDownloaded,
            'latestEbooks' => $latestEbooks,
            'categories' => $categories
        ]);
    }
}
