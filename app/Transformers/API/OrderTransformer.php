<?php

namespace App\Transformers\API;

use App\Models\Address;
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
            'id'                 => $order->id,
            'invoice_id'         => $order->code,
            'courier'            => $order->courier,
            'courier_service'    => $order->courier_service,
            'ongkir'             => $order->ongkir,
            'voucher'            => $order->voucher,
            'discount'           => $order->discount,
            'total'              => $order->total,
            'status'             => $order->status,
            'note'               => $order->note,            
            'is_offline'         => $order->is_offline,            
            'pickup_deadline'    => $order->pickup_deadline ? $order->pickup_deadline->format('d-m-Y') : null,       
            'date'               => $order->created_at->format(config('app.default.datetime_human')),
            'is_taken'           => $order->is_taken,     
            'invoice'            => $this->getInvoice($order),  
        ];      
    }

    public function getInvoice($order)
    {
        if($order->status == 'Completed'){
            return "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf";
        } 

        return null;
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
        $address = $order->address;
        if ($address instanceof Address) {
            return $this->item($address, new AddressTransformer());
        } else {
            return $this->null();
        }
    }
}
