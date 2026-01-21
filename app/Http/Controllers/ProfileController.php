<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Download;
use App\Models\Review;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    /**
     * Afficher le profil de l'utilisateur
     */
    public function show(Request $request): View
    {
        $user = $request->user();
        
        // Récupérer les téléchargements récents
        $recentDownloads = Download::with('ebook')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();
            
        // Récupérer les avis récents
        $recentReviews = Review::with('ebook')
            ->where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();
            
        // Compter le nombre total de téléchargements et d'avis
        $downloadsCount = Download::where('user_id', $user->id)->count();
        $reviewsCount = Review::where('user_id', $user->id)->count();
        
        return view('profile.show', [
            'user' => $user,
            'recentDownloads' => $recentDownloads,
            'recentReviews' => $recentReviews,
            'downloadsCount' => $downloadsCount,
            'reviewsCount' => $reviewsCount,
        ]);
    }

    /**
     * Afficher le formulaire d'édition du profil
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
