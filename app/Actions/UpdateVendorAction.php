<?php

namespace App\Actions;

use App\Models\Vendor;

class UpdateVendorAction
{
    private $vendor;
    private $attributes;

    public function __construct(Vendor $vendor, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->vendor = $vendor;
    }

    public function handle()
    {
        return $this->vendor->fill($this->attributes)->save();
    }
}
