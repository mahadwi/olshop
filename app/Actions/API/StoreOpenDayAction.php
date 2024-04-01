<?php

namespace App\Actions\API;

use App\Models\ClosingDay;
use Illuminate\Support\Facades\DB;

class StoreOpenDayAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {
            
            //if exists
            $model = ClosingDay::whereDate('open', date('Y-m-d'))->first();

            if(!$model){
                $model = new ClosingDay;
            }

            $model->fill(($this->attributes));
        
            $model->save();

            return $model;
        });
        
    }
}