<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\DocumentCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'category_id' => DocumentCategory::inRandomOrder()->first()?->id ?? 1,
            'created_by'  => User::inRandomOrder()->first()?->id ?? 1,
            'status'      => $this->faker->randomElement([
                'draft', 'submitted', 'under_review', 'approved', 'published'
            ]),
            'priority'    => $this->faker->randomElement(['low', 'normal', 'high']),
        ];
    }
}
