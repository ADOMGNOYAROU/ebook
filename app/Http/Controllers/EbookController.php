<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;

class EbookController extends Controller
{
    /**
     * Afficher la liste des ebooks
     */
    public function index()
    {
        $ebooks = Ebook::with('category')
            ->latest()
            ->filter(request(['search', 'category']))
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();

        return view('ebooks.index', [
            'ebooks' => $ebooks,
            'categories' => $categories
        ]);
    }

    /**
     * Afficher les détails d'un ebook
     */
    public function show(Ebook $ebook)
    {
        $relatedEbooks = Ebook::where('category_id', $ebook->category_id)
            ->where('id', '!=', $ebook->id)
            ->take(4)
            ->get();

        return view('ebooks.show', [
            'ebook' => $ebook->load('category'),
            'relatedEbooks' => $relatedEbooks
        ]);
    }
}
