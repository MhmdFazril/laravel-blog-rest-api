<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Sejarah',
            'slug' => 'sejarah',
        ]);

        Category::create([
            'name' => 'Kehidupan Sosial',
            'slug' => 'kehidupan-sosial',
        ]);

        Category::create([
            'name' => 'Kesehatan',
            'slug' => 'kesehatan',
        ]);

        Category::create([
            'name' => 'Hiburan',
            'slug' => 'hiburan',
        ]);

        Category::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);

        Category::create([
            'name' => 'Masak',
            'slug' => 'masak',
        ]);
    }
}
