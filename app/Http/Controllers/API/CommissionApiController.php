<?php

namespace App\Http\Controllers\API;

use App\Models\Commission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\CheckCommissionRequest;
use App\Transformers\API\CommissionTransformer;

class CommissionApiController extends Controller
{
    public function index()
    {
        $data = fractal(Commission::all(), new CommissionTransformer()); 
        
        return $this->apiSuccess($data);
    }

    public function checkCommission(CheckCommissionRequest $request)
    {
        $checkBrand = Commission::with('details')->where('brand_id', $request->brand_id)->get();

        $percent = 0;

        if($checkBrand->isNotEmpty()){
            foreach($checkBrand as $commission){
                $checkCategory = in_array($request->product_category_id, $commission->category_id);
                if($checkCategory){
                    $percent =  $this->getPercent($commission->details, $request->price);
                } 
            }
        } else {
            $otherBrand = Commission::with('details')
            ->whereHas('brand', function ($query){
                $query->where('name', 'ilike', "%other%");
            })->first();    

            if($otherBrand){
                $percent = $this->getPercent($otherBrand->details, $request->price);

                dd($percent);
            }
        }

        return $this->apiSuccess(['percent' => $percent]);

    }

    public function getPercent($details, $price)
    {   
        $percent = 0;
        $exclude = $details->where('max', 0)->values()->all();

        if($exclude){
            $details = $details->where('id', '!=', $exclude[0]->id)->all();    

            $fixPrice = collect($details)->where('min', '<=', $price)
            ->where('max', '>=', $price)->values();

            if($fixPrice->isNotEmpty()){
                return $fixPrice[0]->percent;
            }            

            $percent = $exclude[0]->min >= $price ? $exclude[0]->percent : 0;

            return $percent;

        }
        
        $fixPrice = collect($details)->where('min', '<=', $price)
        ->where('max', '>=', $price)->values();

        if($fixPrice->isNotEmpty()){
            return $fixPrice[0]->percent;
        }      

        return $percent;

    }
}
