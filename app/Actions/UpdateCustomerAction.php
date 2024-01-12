<?php

namespace App\Actions;

use App\Models\Customer;

class UpdateCustomerAction
{
    private $customer;
    private $attributes;

    public function __construct(Customer $customer, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->customer = $customer;
    }

    public function handle()
    {
        return $this->customer->fill($this->attributes)->save();
    }
}
