<?php

namespace App\Helpers;

use App\Models\Contact;
use DateTime;
use App\Models\Profile;
use App\Models\Rekening;
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

    public static function profile(){
        $profile = Profile::first();
        $contact = Contact::first();
        $rekening = Rekening::where('is_default', true)->first();
        

        $profile->address = $contact->address;        
        $profile->phone = $contact->telp;        
        $profile->bank = $rekening->bank;
        $profile->bank_account_number = $rekening->bank_account_number;
        $profile->bank_account_holder = $rekening->bank_account_holder;

        return $profile;
    }

    public static function dateIndo($date)
    {
        $dateTime = new DateTime($date);

        // Daftar nama hari dalam bahasa Indonesia
        $namaHari = array(
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        );

        // Daftar nama bulan dalam bahasa Indonesia
        $namaBulan = array(
            '',
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        // Mendapatkan indeks hari dan bulan
        $hari = $namaHari[$dateTime->format('w')];
        $bulan = $namaBulan[intval($dateTime->format('n'))];
        
        $data = [
            'day'   => $hari,
            'date'  => $dateTime->format('d'),
            'month' => $bulan,
            'year'  => $dateTime->format('Y')
        ];

        return $data;
    }

    public static function dateEnglish($date)
    {
        $dateTime = new DateTime($date);

        // Daftar nama hari dalam bahasa Inggris
        $englishDayNames = array(
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        );

        // Daftar nama bulan dalam bahasa Inggris
        $englishMonthNames = array(
            '',
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        );

        // Mendapatkan indeks hari dan bulan
        $day = $englishDayNames[$dateTime->format('w')];
        $month = $englishMonthNames[intval($dateTime->format('n'))];
        
        $data = [
            'day'   => $day,
            'date'  => $dateTime->format('d'),
            'month' => $month,
            'year'  => $dateTime->format('Y')
        ];

        return $data;
    }

    public static function priceFormat($amount, bool $useDecimal = false, $symbol = null): string
    {
        if (!$symbol) {
            $symbol = '';
        }

        $isNegative = false;
        if ($amount < 0) {
            $isNegative = true;
            $amount = abs($amount);
        }

        $parts = [];
        $parts['symbol'] = $symbol;
        $decimal = $useDecimal ? 2 : 0;
        $parts['amount'] = number_format($amount, $decimal, ',', '.');

        $price = implode('', $parts);

        return $isNegative ? "($price)" : $price;
    }

}