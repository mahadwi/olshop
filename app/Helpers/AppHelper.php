<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class AppHelper{

    public static function BulanRomawi($bulan) {
        $array_romawi = array(
            '1'     => 'I',
            '01'    => 'I',
            '2'     => 'II',
            '02'    => 'II',
            '3'     => 'III',
            '03'    => 'III',
            '4'     => 'IV',
            '04'    => 'IV',
            '5'     => 'V',
            '05'    => 'V',
            '6'     => 'VI',
            '06'    => 'VI',
            '7'     => 'VII',
            '07'    => 'VII',
            '8'     => 'VIII',
            '08'     => 'VIII',
            '9'     => 'IX',
            '09'     => 'IX',
            '10'    => 'X',
            '11'    => 'XI',
            '12'    => 'XII',
        );

        return $array_romawi[$bulan];
    }

    public static function generateNumber($model, $prefix){
        
        
        $class = "App\\Models\\" . $model;
        if (! class_exists($class)) {
            dd('Model not found');
        }

        $instance = new $class;

        $year = date('y');
        $month = self::BulanRomawi(date('m'));

        $period = $prefix.'/'. $month . '/' . $year;

        $length = strlen($period);

        $queryBuilder = $instance->selectRaw('max(substring("nomor", 1, 4)) as kode');
        $queryBuilder->where(DB::raw('substring("nomor", 6, '.$length.')'), '=', $period);

        $number_exist = $queryBuilder->first();

        if (! $number_exist->kode) {
            $sequence = '0001';
        } else {
            $sequence = (int) $number_exist->kode + 1;
            $sequence = str_pad($sequence, 4, "0", STR_PAD_LEFT);
        }

        $nomor = $sequence .'/' . $period;

        return $nomor;

    }

}