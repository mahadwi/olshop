<?php

namespace App\Actions;

use App\Models\Commission;
use App\Models\CommissionDetail;
use Illuminate\Support\Facades\DB;

class UpdateCommissionAction
{
    public function handle(Commission $commission, array $attributes = [])
    {
        return DB::transaction(function () use ($commission, $attributes) {

            $commission->fill($attributes);
            $commission->save();

            $commission->details()->delete();

            //detail
            foreach($attributes['details'] as $detail){
                
                $detail['commission_id'] = $commission->id;                
                
                $detail = new CommissionDetail($detail);

                $detail->save();

            }

            return $commission;
            
        });
    }
}