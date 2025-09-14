<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        
        // Pokreni seedere u svakom testu
        $this->seed([
            \Database\Seeders\UserTypeSeeder::class,
            \Database\Seeders\GameTypeSeeder::class,
            \Database\Seeders\ResultSeeder::class,
        ]);
        // Brzo kreiranje osnovnih podataka za testove
    
    }
}