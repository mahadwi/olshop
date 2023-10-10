<?php

namespace Database\Seeders;

use App\Models\User;
use App\Constants\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();

        //admin
        $a = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
        ]);

        $a->assignRole(Role::ADMIN);

    }
}
