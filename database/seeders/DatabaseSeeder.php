<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\SecretNote::updateOrCreate(
            ['token' => 'demo123'],
            [
                'recipient_name' => 'Someone Special',
                'creator_name' => 'Someone nearby',
                'creator_hint' => 'Someone in the same room.',
                'room_context' => 'Same Room',
                'status' => 'created',
                'current_step' => 1,
                'is_destroyed' => false,
            ]
        );
    }
}
