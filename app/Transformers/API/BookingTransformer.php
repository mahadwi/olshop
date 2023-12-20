<?php

namespace App\Transformers\API;

use App\Models\Booking;
use App\Models\Payment;
use League\Fractal\TransformerAbstract;
use App\Transformers\API\PaymentTransformer;

class BookingTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'payment'
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Booking $booking)
    {
        return [
            'id'        => $booking->id,
            'code'      => $booking->code,
            'qty'       => $booking->qty,
            'total'     => $booking->total,
            'voucher'   => $booking->voucher,
            'message'   => $booking->message,
            'note'      => $booking->note,            
        ];
    }

    public function includePayment($booking)
    {
        $payment = $booking->paymentable;
        if ($payment instanceof Payment) {
            return $this->item($payment, new PaymentTransformer());
        } else {
            return $this->null();
        }
    }

    // public function includeUser($wishlist)
    // {
    //     $user = $wishlist->user;
    //     if ($user instanceof User) {
    //         return $this->item($user, new UserTransformer());
    //     } else {
    //         return $this->null();
    //     }
    // }
}
