<?php

namespace App\Transformers\API;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderPosTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'detail'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Order $order)
    {
        return [
            'id'                 => $order->id,
            'invoice_id'         => $order->code,
            'voucher'            => $order->voucher,
            'discount'           => $order->discount,
            'jumlah'             => $order->jumlah,
            'total'              => $order->total,
            'date'               => $order->created_at->format(config('app.default.datetime_human')),
            'kasir'              => $order->kasir->name,
            'customer'           => $order->user->name,
            'is_pos'             => $order->is_pos,
            'is_pickup'          => $order->is_offline,
        ];      
    }

    public function includeDetail($order)
    {
        return $this->collection($order->orderDetail, new OrderDetailTransformer);        
    }
}
