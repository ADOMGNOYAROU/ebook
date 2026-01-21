<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Afficher la liste des catégories
     */
    public function index()
    {
        $categories = Category::withCount('ebooks')
            ->orderBy('name')
            ->get();

        return view('categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Afficher les ebooks d'une catégorie
     */
    public function show(Category $category)
    {
        $ebooks = $category->ebooks()
            ->latest()
            ->paginate(12);

        return view('categories.show', [
            'category' => $category,
            'ebooks' => $ebooks
        ]);
    }
}
