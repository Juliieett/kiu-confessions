<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Study Life',
            'Crush',
            'Funny',
            'Serious',
            'Professor',
            'Other',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        $this->command->info('✅ Seeded ' . count($categories) . ' categories.');
    }
}
