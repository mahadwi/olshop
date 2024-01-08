<?php

namespace App\Transformers\API;

use App\Models\Order;
use App\Models\Payment;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'payment', 'detail', 'address'
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
            'id'        => $order->id,
            'courier'   => $order->courier,
            'ongkir'    => $order->ongkir,
            'voucher'   => $order->voucher,
            'discount'  => $order->discount,
            'total'     => $order->total,
            'status'    => $order->status,
            'note'      => $order->note,            
        ];
    }

    public function includePayment($order)
    {
        $payment = $order->paymentable;
        if ($payment instanceof Payment) {
            return $this->item($payment, new PaymentTransformer());
        } else {
            return $this->null();
        }
    }

    public function includeDetail($order)
    {
        return $this->collection($order->orderDetail, new OrderDetailTransformer);        
    }

    public function includeAddress($order)
    {
        return $this->item($order->address, new AddressTransformer());
    }
}