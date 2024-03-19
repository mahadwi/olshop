<?php

namespace App\Actions;

use App\Models\Order;

class SetCompletedOrderAction
{
    private $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }


    public function handle()
    {

        $this->order->status = 'Completed';

        $this->order->save();

        return $this->order;
      
    }
}