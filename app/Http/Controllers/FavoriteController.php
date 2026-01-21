<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Favorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(Request $request): View
    {
        $favorites = Favorite::query()
            ->where('user_id', Auth::id())
            ->with(['ebook.category'])
            ->latest()
            ->paginate(10);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Ebook $ebook): RedirectResponse
    {
        $favorite = Favorite::query()
            ->where('user_id', Auth::id())
            ->where('ebook_id', $ebook->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return back()->with('success', 'E-book retiré des favoris.');
        }

        Favorite::create([
            'user_id' => Auth::id(),
            'ebook_id' => $ebook->id,
        ]);

        return back()->with('success', 'E-book ajouté aux favoris.');
    }
}
