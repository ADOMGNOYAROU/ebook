<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use App\Models\Download;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PublicEbookController extends Controller
{
    /**
     * Afficher la liste des ebooks avec filtres
     */
    public function index(Request $request)
    {
        $query = Ebook::query()->with('category');
        
        // Recherche par titre/auteur
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%");
            });
        }

        // Filtre par catégorie
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        // Filtre gratuit/payant
        if ($request->has('type')) {
            $query->where('is_free', $request->input('type') === 'free');
        }

        // Filtre par langue
        if ($request->has('language') && $request->input('language') !== 'all') {
            $query->where('language', $request->input('language'));
        }

        // Tri
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('downloads_count', 'desc');
                break;
            case 'title':
                $query->orderBy('title');
                break;
            default: // latest
                $query->latest();
                break;
        }

        $ebooks = $query->paginate(12);
        $categories = Category::withCount('ebooks')->orderBy('name')->get();
        $languages = Ebook::select('language')->distinct()->pluck('language');

        return view('ebooks.index', [
            'ebooks' => $ebooks,
            'categories' => $categories,
            'languages' => $languages,
            'filters' => $request->all(),
        ]);
    }

    /**
     * Afficher les détails d'un ebook
     */
    public function show($slug)
    {
        $ebook = Ebook::where('slug', $slug)->with('category')->firstOrFail();
        
        // Récupérer des ebooks similaires
        $similarEbooks = Ebook::where('category_id', $ebook->category_id)
            ->where('id', '!=', $ebook->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Vérifier si l'utilisateur connecté a déjà téléchargé cet ebook
        $hasDownloaded = false;
        $userReview = null;
        
        if (auth()->check()) {
            $hasDownloaded = Download::where('user_id', auth()->id())
                ->where('ebook_id', $ebook->id)
                ->exists();
                
            // Vérifier si l'utilisateur a déjà laissé un avis
            $userReview = Review::where('user_id', auth()->id())
                ->where('ebook_id', $ebook->id)
                ->first();
        }
        
        // Récupérer les avis approuvés
        $reviews = $ebook->reviews()->where('approved', true)->with('user')->latest()->get();
        
        // Calculer la note moyenne
        $averageRating = $reviews->avg('rating') ?? 0;

        return view('ebooks.show', [
            'ebook' => $ebook,
            'similarEbooks' => $similarEbooks,
            'hasDownloaded' => $hasDownloaded,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'userReview' => $userReview
        ]);
    }

    /**
     * Afficher les ebooks d'une catégorie
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $ebooks = Ebook::where('category_id', $category->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('categories.show', compact('category', 'ebooks'));
    }

    /**
     * Afficher la liste des catégories
     */
    public function categoriesIndex()
    {
        $categories = Category::withCount('ebooks')
            ->orderBy('name')
            ->get();
            
        return view('categories.index', compact('categories'));
    }

    /**
     * Télécharger un ebook
     */
    public function download(Ebook $ebook)
    {
        // Vérifier si l'utilisateur est connecté
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour télécharger cet ebook.');
        }

        $user = auth()->user();
        
        // Vérifier si l'ebook est gratuit ou si l'utilisateur l'a acheté
        if (!$ebook->is_free) {
            // Vérifier si l'utilisateur a acheté cet ebook
            if (!$user->hasPurchased($ebook->id)) {
                return redirect()->route('checkout', $ebook)
                    ->with('info', 'Veuillez finaliser votre achat pour télécharger cet ebook.');
            }
        }

        // Vérifier si le fichier existe
        if (!Storage::disk('public')->exists($ebook->file_path)) {
            return back()->with('error', 'Le fichier de l\'ebook est introuvable.');
        }

        // Enregistrer le téléchargement dans l'historique
        $download = Download::firstOrNew([
            'user_id' => $user->id,
            'ebook_id' => $ebook->id
        ]);
        
        $download->ip_address = request()->ip();
        $download->user_agent = request()->userAgent();
        $download->save();

        // Incrémenter le compteur de téléchargements
        $ebook->increment('downloads_count');

        // Retourner le fichier pour téléchargement
        return Storage::disk('public')->download(
            $ebook->file_path,
            $ebook->slug . '.' . pathinfo($ebook->file_path, PATHINFO_EXTENSION),
            ['Content-Type' => 'application/octet-stream']
        );
    }
}
