<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class FaqSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('faq_sections')->truncate();
        
        $sections = [
            ['section' => 'Pesanan', 'section_en' => 'Order'],
            ['section' => 'Akun', 'section_en' => 'Account'],
            ['section' => 'Lainnya', 'section_en' => 'Other'],
        ];
        DB::table('faq_sections')->insert($sections);
    }
}
