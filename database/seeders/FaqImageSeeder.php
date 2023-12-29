<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FaqImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faq_images')->truncate();

        $image = [
            ['image' => 'default.jpg'],
        ];
        DB::table('faq_images')->insert($image);
    }
}
