<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Roman', 'slug' => 'roman', 'icon' => '📖'],
            ['name' => 'Science-Fiction', 'slug' => 'science-fiction', 'icon' => '🚀'],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'icon' => '🧙'],
            ['name' => 'Policier', 'slug' => 'policier', 'icon' => '🕵️'],
            ['name' => 'Biographie', 'slug' => 'biographie', 'icon' => '👤'],
            ['name' => 'Développement Personnel', 'slug' => 'developpement-personnel', 'icon' => '💪'],
            ['name' => 'Histoire', 'slug' => 'histoire', 'icon' => '🏛️'],
            ['name' => 'Science', 'slug' => 'science', 'icon' => '🔬'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
