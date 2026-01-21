<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Ebook;
use App\Models\Review;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Rediriger les administrateurs vers le tableau de bord d'administration
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        // Récupérer les données pour le tableau de bord
        $userDownloads = $user->downloads()->with('ebook')->latest()->take(5)->get();
        $userReviews = $user->reviews()->with('ebook')->latest()->take(5)->get();
        $userFavorites = $user->favorites()->with('ebook')->latest()->take(5)->get();
        
        // Récupérer les notifications non lues
        $unreadNotifications = $user->unreadNotifications;
        
        // Récupérer les statistiques
        $stats = [
            'downloads_count' => $user->downloads()->count(),
            'reviews_count' => $user->reviews()->count(),
            'favorites_count' => $user->favorites()->count(),
            'recent_activity' => $this->getRecentActivity($user)
        ];
        
        // Récupérer les ebooks recommandés
        $recommendedEbooks = $this->getRecommendedEbooks($user);
        
        return view('dashboard.index', [
            'userDownloads' => $userDownloads,
            'userReviews' => $userReviews,
            'userFavorites' => $userFavorites,
            'stats' => $stats,
            'recommendedEbooks' => $recommendedEbooks,
            'unreadNotifications' => $unreadNotifications
        ]);
    }
    
    /**
     * Récupérer l'activité récente de l'utilisateur
     */
    protected function getRecentActivity($user)
    {
        $activities = collect();
        
        // Derniers téléchargements
        $downloads = $user->downloads()
            ->with('ebook')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($download) {
                return [
                    'type' => 'download',
                    'icon' => 'download',
                    'color' => 'text-blue-500',
                    'text' => 'Vous avez téléchargé "' . $download->ebook->title . '"',
                    'time' => $download->created_at->diffForHumans()
                ];
            });
            
        // Derniers avis
        $reviews = $user->reviews()
            ->with('ebook')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($review) {
                return [
                    'type' => 'review',
                    'icon' => 'star',
                    'color' => 'text-yellow-500',
                    'text' => 'Vous avez noté "' . $review->ebook->title . '" avec ' . $review->rating . ' étoiles',
                    'time' => $review->created_at->diffForHumans()
                ];
            });
            
        // Derniers favoris
        $favorites = $user->favorites()
            ->with('ebook')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($favorite) {
                return [
                    'type' => 'favorite',
                    'icon' => 'heart',
                    'color' => 'text-red-500',
                    'text' => 'Vous avez ajouté "' . $favorite->ebook->title . '" à vos favoris',
                    'time' => $favorite->created_at->diffForHumans()
                ];
            });
        
        // Fusionner et trier par date
        return $activities->merge($downloads)
                         ->merge($reviews)
                         ->merge($favorites)
                         ->sortByDesc('time')
                         ->take(5);
    }
    
    /**
     * Récupérer les ebooks recommandés pour l'utilisateur
     */
    protected function getRecommendedEbooks($user)
    {
        $recentDownloads = $user->downloads()->with('ebook')->latest()->take(5)->get();
        $recommendedEbooks = collect();
        
        // Si l'utilisateur a des téléchargements, on peut suggérer des ebooks similaires
        if ($recentDownloads->isNotEmpty()) {
            // Récupérer les catégories des ebooks téléchargés
            $categoryIds = $recentDownloads->pluck('ebook.category_id')->unique()->toArray();
            
            // Récupérer les IDs des ebooks déjà téléchargés
            $downloadedEbookIds = $recentDownloads->pluck('ebook_id')->toArray();
            
            // Récupérer des ebooks des mêmes catégories (sauf ceux déjà téléchargés)
            $recommendedEbooks = Ebook::whereIn('category_id', $categoryIds)
                ->whereNotIn('id', $downloadedEbookIds)
                ->where('is_published', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
                
            // Si on n'a pas assez d'ebooks recommandés, on en ajoute d'autres au hasard
            if ($recommendedEbooks->count() < 4) {
                $additionalCount = 4 - $recommendedEbooks->count();
                $additionalEbooks = Ebook::whereNotIn('id', array_merge($downloadedEbookIds, $recommendedEbooks->pluck('id')->toArray()))
                    ->where('is_published', true)
                    ->inRandomOrder()
                    ->take($additionalCount)
                    ->get();
                    
                $recommendedEbooks = $recommendedEbooks->merge($additionalEbooks);
            }
        } else {
            // Si aucun téléchargement, on affiche les ebooks les plus populaires
            $recommendedEbooks = Ebook::where('is_published', true)
                ->orderBy('downloads_count', 'desc')
                ->take(4)
                ->get();
        }
        
        return $recommendedEbooks;
    }
}
