<?php

namespace App\Transformers\API;

use App\Models\Payment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
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
    public function transform(Payment $payment)
    {
        return [
            "status"                => $payment->status,
            "invoice_url"           => $payment->invoice_url,
            "expired"               => $payment->expired,
            "payment_method"        => $payment->payment_method,
            "payment_channel"       => $payment->payment_channel,
        ];
    }
}
