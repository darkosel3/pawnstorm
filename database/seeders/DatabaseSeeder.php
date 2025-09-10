<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // $this->call(FriendshipsSeeder::class);
        $this->call(UserTypeSeeder::class);
        $this->call(ResultSeeder::class);
        $this->call(GameTypeSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(FriendshipSeeder::class);
        $this->call(GameSeeder::class);

    }
}
