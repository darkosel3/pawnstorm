<?php

namespace Database\Seeders;

use App\Models\Friendships;
use Illuminate\Database\Seeder;

class FriendshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Friendships::factory()
            ->count(5)
            ->create();
    }
}
