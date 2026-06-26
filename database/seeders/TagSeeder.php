<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            'Exam',
            'Dorm',
            'Library',
            'Campus',
            'Group Project',
            'Late Night',
            'Cafeteria',
            'WiFi',
        ];

        foreach ($tags as $name) {
            Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }

        $this->command->info('✅ Seeded ' . count($tags) . ' tags.');
    }
}
