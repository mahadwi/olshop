<?php

namespace App\Actions;

use App\Models\Operational;
use App\Models\PickupDuration;
use Illuminate\Support\Facades\DB;

class StoreOperationAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {
           
            $duration = PickupDuration::updateOrCreate(
                ['id' => 1],
                [
                    'duration' => $this->attributes['duration']
                ]
            );

            //operational
            foreach($this->attributes['operationals'] as $ops){
                
                $params = [
                    'is_open' => $ops['is_open'],
                    'open'    => $ops['time'][0],
                    'close' => $ops['time'][1],
                ];

                $model = Operational::find($ops['id']);
                
                $model->fill($params)->save();
            }

            return $duration;
        });
    }
}