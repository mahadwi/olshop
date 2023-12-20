<?php

namespace App\Actions;

use App\Models\Payment;

class UpdatePaymentAction
{
    private $payment;
    private $attributes;

    public function __construct(Payment $payment, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->payment = $payment;
    }

    public function handle()
    {
        $this->payment->fill($this->attributes)->save();

        return $this->payment;
    }
}
