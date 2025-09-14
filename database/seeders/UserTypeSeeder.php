<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            ['user_type_id' => 1, 'name' => 'admin'],
            ['user_type_id' => 2, 'name' => 'premium'],
            ['user_type_id' => 3, 'name' => 'free']
        ]);
    }
}
