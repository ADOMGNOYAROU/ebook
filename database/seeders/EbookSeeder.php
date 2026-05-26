<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EbookSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('  → Lancement du téléchargement des assets réels...');
        $this->command->call('ebooks:download-assets');
    }
}
