<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Raka',
            'email' => 'raka@flipplay.test',
        ]);

        User::factory()->create([
            'name' => 'Nadia',
            'email' => 'nadia@flipplay.test',
        ]);

        $initialMedia = [
            [
                'title' => 'Attack on Titan',
                'type' => 'Anime',
                'genre' => 'Action',
                'episodes' => 25,
                'duration_minutes' => null,
            ],
            [
                'title' => 'Spy x Family',
                'type' => 'Anime',
                'genre' => 'Comedy',
                'episodes' => 25,
                'duration_minutes' => null,
            ],
            [
                'title' => 'Your Lie in April',
                'type' => 'Anime',
                'genre' => 'Drama, Romance',
                'episodes' => 22,
                'duration_minutes' => null,
            ],
            [
                'title' => 'A Silent Voice',
                'type' => 'Film',
                'genre' => 'Drama',
                'episodes' => null,
                'duration_minutes' => 130,
            ],
        ];

        foreach ($initialMedia as $item) {
            Media::create($item);
        }
    }
}
