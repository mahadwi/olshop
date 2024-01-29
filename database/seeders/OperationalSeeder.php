<?php

namespace Database\Seeders;

use App\Models\Operational;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use PHPUnit\Framework\Constraint\Operator;

class OperationalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('operationals')->truncate();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $open = '08:00';
        $close = '17:00';

        foreach($days as $day){
            $params = [
                'day'   => $day,
                'open'  => $open,
                'close' => $close
            ];

            Operational::create($params);

        }

    }
}
