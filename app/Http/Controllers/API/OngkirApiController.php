<?php

namespace App\Http\Controllers\API;

use stdClass;
use App\Models\Profile;
use App\Constants\Courier;
use Illuminate\Http\Request;
use Dipantry\Rajaongkir\Rajaongkir;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\OngkirApiRequest;

class OngkirApiController extends Controller
{
    public function ongkir(OngkirApiRequest $request)
    {
        
        if($request->courier == Courier::PICKUP){
            return $this->apiSuccess([$this->getPickup()]);
        }

        $origin = $this->getOrigin();

        if(!$origin) return $this->apiError([], [], 'Origin Not Set');
        
        $ongkir = \Rajaongkir::getOngkirCost(
            $origin = $origin->subdistrict_id, $destination = $request->destination, $weight = $request->weight, $courier = $request->courier,
            $originType = 'subdistrict', $destinationType = 'subdistrict'
        );        

        $dolar = \Rajaongkir::getCurrency();        

        $cost = collect($ongkir[0]['costs'])->map(function($cost) use ($dolar) {
            $cost['cost'] = collect($cost['cost'])->map(function($biaya) use ($dolar) {
                $biaya['value_usd'] = $biaya['value'] * $dolar['value'];
                return $biaya;
            })->all();
        
            return $cost;
        })->all();

        $cost[0]['idr_dolar'] = $dolar['value'];

        return $this->apiSuccess($cost);
    }


    public function courier()
    {
        $data = Courier::getValues();

        return $this->apiSuccess($data);
    }

    public function getOrigin()
    {
        $origin = Profile::first();

        return $origin;
    }

    public function getPickup()
    {
        $response = new stdClass();

        $response->service = 'Luxury Hub Store';
        $response->description = '';
        $response->cost = [
            [
                'value'     => 0,
                'etd'       => '',
                'note'      => '',
                'value_usd' => 0
            ]
        ];
        
        return $response;
        
    }
}
