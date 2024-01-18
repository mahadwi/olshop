<?php

namespace App\Actions;

use App\Models\VendorProduct;

class UpdateVendorProductAction
{
    private $vendorProduct;
    private $attributes;

    public function __construct(VendorProduct $vendorProduct, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->vendorProduct = $vendorProduct;
    }

    public function handle()
    {
        return $this->vendorProduct->fill($this->attributes)->save();
    }
}
