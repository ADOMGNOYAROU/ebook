<?php

namespace Database\Seeders;

use App\Models\Ebook;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EbookSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::all();
        
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }

        $ebooks = [
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'description' => 'Un conte poétique et philosophique pour enfants et adultes.',
                'is_free' => true,
                'pages' => 97,
                'language' => 'fr',
                'downloads_count' => rand(100, 1000),
                'file_size' => 1024 * 1024 * 2.5, // 2.5 MB
                'file_path' => 'ebooks/le-petit-prince.pdf',
                'cover_path' => 'covers/le-petit-prince.jpg',
                'slug' => 'le-petit-prince'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'description' => 'Un roman dystopique sur une société totalitaire.',
                'is_free' => false,
                'pages' => 328,
                'language' => 'fr',
                'downloads_count' => rand(50, 800),
                'file_size' => 1024 * 1024 * 3.2,
                'file_path' => 'ebooks/1984.pdf',
                'cover_path' => 'covers/1984.jpg',
                'slug' => '1984'
            ],
            [
                'title' => 'Le Seigneur des Anneaux',
                'author' => 'J.R.R. Tolkien',
                'description' => 'Une épopée fantastique dans la Terre du Milieu.',
                'is_free' => false,
                'pages' => 1216,
                'language' => 'fr',
                'downloads_count' => rand(200, 1500),
                'file_size' => 1024 * 1024 * 5.8,
                'file_path' => 'ebooks/le-seigneur-des-anneaux.pdf',
                'cover_path' => 'covers/le-seigneur-des-anneaux.jpg',
                'slug' => 'le-seigneur-des-anneaux'
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'description' => 'Un classique de la science-fiction se déroulant sur la planète désertique d\'Arrakis.',
                'is_free' => true,
                'pages' => 512,
                'language' => 'fr',
                'downloads_count' => rand(150, 1200),
                'file_size' => 1024 * 1024 * 4.1,
                'file_path' => 'ebooks/dune.pdf',
                'cover_path' => 'covers/dune.jpg',
                'slug' => 'dune'
            ],
            [
                'title' => 'Le Comte de Monte-Cristo',
                'author' => 'Alexandre Dumas',
                'description' => 'Une histoire de trahison, de vengeance et de rédemption.',
                'is_free' => true,
                'pages' => 1312,
                'language' => 'fr',
                'downloads_count' => rand(80, 900),
                'file_size' => 1024 * 1024 * 6.7,
                'file_path' => 'ebooks/le-comte-de-monte-cristo.pdf',
                'cover_path' => 'covers/le-comte-de-monte-cristo.jpg',
                'slug' => 'le-comte-de-monte-cristo'
            ]
        ];

        foreach ($ebooks as $ebookData) {
            $categoryId = $categories->random()->id;
            
            $ebook = new Ebook($ebookData);
            $ebook->category_id = $categoryId;
            $ebook->save();
        }
    }
}
