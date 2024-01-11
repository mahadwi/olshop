<?php

namespace App\Http\Controllers\API;

use App\Constants\Courier;
use Illuminate\Http\Request;
use Dipantry\Rajaongkir\Rajaongkir;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OngkirApiRequest;

class OngkirApiController extends Controller
{
    public function ongkir(OngkirApiRequest $request)
    {
        
        $ongkir = \Rajaongkir::getOngkirCost(
            $origin = 2096, $destination = $request->destination, $weight = $request->weight, $courier = $request->courier,
            $originType = 'subdistrict', $destinationType = 'subdistrict'
        );

        
        return $this->apiSuccess($ongkir);
    }


    public function courier()
    {
        $data = Courier::getValues();

        return $this->apiSuccess($data);
    }
}
