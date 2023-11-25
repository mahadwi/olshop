<?php

namespace App\Actions;

use App\Models\CustomerCare;

class UpdateCustomerCareAction
{
    private $termCondition;
    private $attributes;


    public function __construct(CustomerCare $customerCare, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->customerCare = $customerCare;
    }

    public function handle()
    {
        $this->customerCare->fill($this->attributes)->save();
        return $this->customerCare;
    }
}
