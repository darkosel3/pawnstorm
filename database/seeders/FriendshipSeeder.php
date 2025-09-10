<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Friendship::factory()
            ->count(5)
            ->create();
    }
}
