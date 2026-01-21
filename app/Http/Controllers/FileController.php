<?php

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function downloadEbook(Ebook $ebook)
    {
        if (!auth()->check()) {
            abort(403, 'Accès non autorisé');
        }

        $filePath = 'ebooks/' . $ebook->file_path;
        
        if (!Storage::disk('local')->exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }

        // Enregistrer le téléchargement
        Download::create([
            'user_id' => auth()->id(),
            'ebook_id' => $ebook->id,
            'downloaded_at' => now(),
        ]);

        return Storage::disk('local')->download($filePath, $ebook->original_filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $ebook->original_filename . '"',
        ]);
    }

    public function showCover($filename)
    {
        $path = 'covers/' . $filename;
        
        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(storage_path('app/public/' . $path));
    }
}
