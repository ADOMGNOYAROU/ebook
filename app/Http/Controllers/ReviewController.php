<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ebook_id' => 'required|exists:ebooks,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $ebook = Ebook::findOrFail($request->ebook_id);

        // Vérifier si l'utilisateur a déjà laissé un avis
        $existingReview = Review::where('user_id', $user->id)
            ->where('ebook_id', $ebook->id)
            ->first();

        if ($existingReview) {
            // Mettre à jour l'avis existant
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            return back()->with('success', 'Votre avis a été mis à jour.');
        }

        // Créer un nouvel avis
        Review::create([
            'user_id' => $user->id,
            'ebook_id' => $ebook->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'approved' => true,
        ]);

        return back()->with('success', 'Merci pour votre avis !');
    }

    public function destroy(Review $review)
    {
        // Vérifier que l'utilisateur est le propriétaire de l'avis
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Votre avis a été supprimé.');
    }
}
