<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GameTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('game_types')->insert([
            ['naziv' => 'Bullet', 'time_format' => '60', 'increment' => '0'],
            ['naziv' => 'Bullet', 'time_format' => '60', 'increment' => '1'],
            ['naziv' => 'Bullet', 'time_format' => '120', 'increment' => '1'],
            ['naziv' => 'Blitz', 'time_format' => '180', 'increment' => '0'],
            ['naziv' => 'Blitz', 'time_format' => '180', 'increment' => '2'],
            ['naziv' => 'Blitz', 'time_format' => '300', 'increment' => '0'],
            ['naziv' => 'Rapid', 'time_format' => '600', 'increment' => '0'],
            ['naziv' => 'Rapid', 'time_format' => '900', 'increment' => '10'],
            ['naziv' => 'Rapid', 'time_format' => '1800', 'increment' => '0'],
        ]);
    }
}
