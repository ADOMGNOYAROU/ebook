<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DownloadController extends Controller
{
    /**
     * Afficher l'historique des téléchargements de l'utilisateur
     */
    public function index()
    {
        $downloads = Auth::user()->downloads()->with('ebook')->latest()->paginate(10);
        return view('downloads.history', compact('downloads'));
    }

    /**
     * Afficher l'historique des téléchargements (avec filtres)
     */
    public function history(Request $request)
    {
        $query = Auth::user()->downloads()->with('ebook');

        $filter = $request->query('filter');
        if ($filter === 'last_month') {
            $query->where('created_at', '>=', now()->subDays(30));
        }
        if ($filter === 'last_year') {
            $query->where('created_at', '>=', now()->startOfYear());
        }

        $downloads = $query->latest()->paginate(10)->withQueryString();

        return view('downloads.history', compact('downloads'));
    }

    /**
     * Télécharger un ebook
     */
    public function store(Ebook $ebook)
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Vous devez être connecté pour télécharger cet ebook.',
                'redirect' => route('login')
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Vérifier si l'utilisateur est admin ou si l'ebook est gratuit
        if (!auth()->user()->isAdmin() && !$ebook->is_free) {
            // Vérifier si l'utilisateur a déjà acheté l'ebook
            if (!auth()->user()->hasPurchasedEbook($ebook->id)) {
                return response()->json([
                    'message' => 'Vous devez acheter cet ebook avant de pouvoir le télécharger.',
                    'redirect' => route('ebooks.show', $ebook->slug)
                ], Response::HTTP_FORBIDDEN);
            }
        }

        try {
            // Enregistrer le téléchargement dans l'historique
            Download::create([
                'user_id' => auth()->id(),
                'ebook_id' => $ebook->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            // Si le fichier existe, on le renvoie
            if (Storage::disk('public')->exists($ebook->file_path)) {
                $ebook->increment('download_count');
                return Storage::disk('public')->download($ebook->file_path, $ebook->slug . '.pdf');
            }

            return response()->json([
                'message' => 'Le fichier de l\'ebook est introuvable.'
            ], Response::HTTP_NOT_FOUND);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Une erreur est survenue lors du téléchargement.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
