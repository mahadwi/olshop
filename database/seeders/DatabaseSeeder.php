<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('production')) {
            exit('I just saved you from getting fired. Thank me later. John Doe.');
        }

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);
    }
}
