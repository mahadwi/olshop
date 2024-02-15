<?php

namespace App\Actions;

use App\Models\Commission;
use App\Models\CommissionDetail;
use Illuminate\Support\Facades\DB;

class StoreCommissionAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {
           
            $commission = new Commission($this->attributes);
        
            $commission->save();   

            //detail
            foreach($this->attributes['details'] as $detail){
                
                $detail['commission_id'] = $commission->id;                
                
                $detail = new CommissionDetail($detail);

                $detail->save();

            }

            return $commission;
        });
    }
}