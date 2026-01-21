<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ebook;
use App\Models\User;
use App\Models\Download;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord administrateur
     */
    public function index()
    {
        $stats = [
            'totalEbooks' => Ebook::count(),
            'totalUsers' => User::count(),
            'totalDownloads' => Download::count(),
            'totalCategories' => Category::count(),
            'recentEbooks' => Ebook::latest()->take(5)->get(),
            'recentUsers' => User::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Afficher les statistiques détaillées
     */
    public function stats()
    {
        $downloadsByMonth = Download::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $usersByMonth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $topEbooks = Ebook::withCount('downloads')
            ->orderByDesc('downloads_count')
            ->take(10)
            ->get();

        return view('admin.stats', [
            'downloadsByMonth' => $downloadsByMonth,
            'usersByMonth' => $usersByMonth,
            'topEbooks' => $topEbooks
        ]);
    }
}
