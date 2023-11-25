<?php

namespace App\Actions;

use App\Models\CustomerCare;

class StoreCustomerCareAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $customerCare = new CustomerCare($this->attributes);
        $customerCare->save();

        return $customerCare;
    }
}
