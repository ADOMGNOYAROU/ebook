<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEbookRequest;
use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookController extends Controller
{
    /**
     * Affiche la liste des ebooks avec pagination
     */
    public function index()
    {
        $ebooks = Ebook::with('category')
            ->latest()
            ->paginate(10);

        $categories = Category::all();

        return view('admin.ebooks.index', compact('ebooks', 'categories'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel ebook
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.ebooks.create', compact('categories'));
    }

    /**
     * Enregistre un nouvel ebook dans la base de données
     */
    public function store(StoreEbookRequest $request)
    {
        // Traitement du fichier PDF
        $filePath = $request->file('file')->store('ebooks', 'local');
        
        // Traitement de l'image de couverture
        $coverPath = $request->file('cover')->store('covers', 'public');

        // Création du slug à partir du titre
        $slug = $this->generateSlug($request->title);

        // Création de l'ebook
        $ebook = Ebook::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'author' => $request->author,
            'file_path' => $filePath,
            'cover_path' => $coverPath,
            'category_id' => $request->category_id,
            'pages' => $request->pages,
            'language' => $request->language,
            'is_free' => $request->boolean('is_free'),
            'file_size' => $request->file('file')->getSize(),
        ]);

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'L\'ebook a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un ebook
     */
    public function show(Ebook $ebook)
    {
        return view('admin.ebooks.show', compact('ebook'));
    }

    /**
     * Affiche le formulaire de modification d'un ebook
     */
    public function edit(Ebook $ebook)
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.ebooks.edit', compact('ebook', 'categories'));
    }

    /**
     * Met à jour un ebook dans la base de données
     */
    public function update(StoreEbookRequest $request, Ebook $ebook)
    {
        $data = $request->validated();
        
        // Gestion du fichier PDF
        if ($request->hasFile('file')) {
            // Supprimer l'ancien fichier
            Storage::disk('local')->delete($ebook->file_path);
            
            // Stocker le nouveau fichier
            $data['file_path'] = $request->file('file')->store('ebooks', 'local');
            $data['file_size'] = $request->file('file')->getSize();
        }

        // Gestion de l'image de couverture
        if ($request->hasFile('cover')) {
            // Supprimer l'ancienne couverture
            Storage::disk('public')->delete($ebook->cover_path);
            
            // Stocker la nouvelle couverture
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        // Mise à jour du slug si le titre a changé
        if ($ebook->title !== $request->title) {
            $data['slug'] = $this->generateSlug($request->title);
        }

        $ebook->update($data);

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'L\'ebook a été mis à jour avec succès.');
    }

    /**
     * Supprime un ebook de la base de données
     */
    public function destroy(Ebook $ebook)
    {
        // Supprimer les fichiers associés
        Storage::disk('local')->delete($ebook->file_path);
        Storage::disk('public')->delete($ebook->cover_path);
        
        // Supprimer l'ebook
        $ebook->delete();

        return redirect()->route('admin.ebooks.index')
            ->with('success', 'L\'ebook a été supprimé avec succès.');
    }

    /**
     * Génère un slug unique à partir d'un titre
     */
    private function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = Ebook::where('slug', 'LIKE', "{$slug}%")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }
}