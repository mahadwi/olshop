<?php

namespace App\Actions;

use App\Models\Order;
use App\Mail\OrderMail;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\Mail;

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

        //generate invoice
        (new OrderService)->generateInvoice($this->order);

        Mail::to($this->order->user->email)->send(new OrderMail($this->order));

        return $this->order;
      
    }
}