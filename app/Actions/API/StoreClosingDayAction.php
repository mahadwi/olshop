<?php

namespace App\Actions\API;

use App\Models\Order;
use App\Models\ClosingDay;
use Illuminate\Support\Facades\DB;

class StoreClosingDayAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $today = date('Y-m-d');

            $orders = Order::whereDate('created_at', $today)
            ->where('is_pos', true)
            ->where('is_offline', true);

            $cashIn = $orders->sum('pay');
            $cashOut = $orders->sum('return');
            //if exists
            $model = ClosingDay::whereDate('open', $today)->first();

            $total = ($model->starting_cash + $cashIn) - $cashOut;

            $dataUpdate = [
                'cash_in'       => $cashIn,
                'cash_out'      => $cashOut,
                'total_cash'    => $total,
            ];

            $updateArray = array_merge($this->attributes, $dataUpdate);

            $model->fill(($updateArray));
        
            $model->save();
            return $model;
        });
        
    }
}