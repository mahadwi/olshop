<?php

namespace App\Actions\API;

use App\Models\OrderDetail;

class StoreOrderDetailAction
{
    private $attributes;
    private $order;

    public function __construct(array $attributes = [], $order)
    {
        $this->attributes = $attributes;
        $this->order = $order;
    }

    public function handle()
    {
        $detail = new OrderDetail($this->attributes);
        $detail->order_id = $this->order->id;
        $detail->save();

        return $detail;
    }
}
