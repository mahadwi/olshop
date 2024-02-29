<?php

namespace App\Services\Ongkir;

use Illuminate\Support\Facades\Http;

class OngkirService
{
    private $apiKey;
    private $url;

    public function __construct()
    {
        $this->apiKey = config('app.default.ongkir_key');
        $this->url = 'https://api.binderbyte.com/v1/';
    }

    public function cekResi($courier, $resi)
    {

        $url = "{$this->url}track?api_key={$this->apiKey}&courier={$courier}&awb={$resi}";

        $response = Http::get($url)->collect();

        return $response;
    }


}