<?php

namespace App\Helpers;

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

}