<?php

namespace App\Actions\API;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class UpdateOrderPickupAction
{
    public function handle(Order $order, array $attributes = [])
    {
        return DB::transaction(function () use ($order, $attributes) {

            $order->is_taken = $attributes['is_taken'];
            $order->save();

            return $order;
            
        });
    }

    
}