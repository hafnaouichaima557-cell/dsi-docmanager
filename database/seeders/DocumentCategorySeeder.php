<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Procédure',    'slug' => 'procedure'],
            ['name' => 'Politique',    'slug' => 'politique'],
            ['name' => 'Guide',        'slug' => 'guide'],
            ['name' => 'Formulaire',   'slug' => 'formulaire'],
            ['name' => 'Rapport',      'slug' => 'rapport'],
        ];
        foreach ($categories as $category) {
            DB::table('document_categories')->insertOrIgnore([
                'name'       => $category['name'],
                'slug'       => $category['slug'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}