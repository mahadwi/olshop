<?php

namespace App\Actions;

use App\Models\Payment;

class StorePaymentAction
{
    private $attributes;
    private $model;

    public function __construct(array $attributes = [], $model)
    {
        $this->attributes = $attributes;
        $this->model = $model;
    }

    public function handle()
    {
        $payment = new Payment($this->attributes);

        $payment->paymentable()->associate($this->model)->save();

        return $payment;
       
    }
}
