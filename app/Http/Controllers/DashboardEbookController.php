<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ebook;
use Illuminate\Http\Request;

class DashboardEbookController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('ebooks')->get();

        $ebooks = Ebook::with('category')
            ->when($request->category, function($q) use ($request) {
                $q->whereHas('category', fn($cat) => $cat->where('slug', $request->category));
            })
            ->when($request->type, function($q) use ($request) {
                if($request->type === 'free') $q->where('is_free', true);
                elseif($request->type === 'paid') $q->where('is_free', false);
            })
            ->when($request->search, function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('author', 'like', '%'.$request->search.'%');
            })
            ->when($request->sort, function($q) use ($request) {
                if($request->sort === 'popular') $q->orderBy('downloads_count', 'desc');
                elseif($request->sort === 'latest') $q->orderBy('created_at', 'desc');
                elseif($request->sort === 'rating') $q->orderBy('reviews_avg_rating', 'desc');
                else $q->orderBy('created_at', 'desc');
            })
            ->paginate(12);

        $ebooks->appends($request->query());

        return view('ebooks.index-dashboard', compact('categories', 'ebooks'));
    }
}
