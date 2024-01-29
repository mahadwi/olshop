<?php

namespace Database\Seeders;

use App\Models\PickupDuration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PickupDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pickup_durations')->truncate();

        $data = [
            'duration' => 3,
        ];

        PickupDuration::create($data);
    }
}
