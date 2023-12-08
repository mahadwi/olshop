<?php

namespace App\Actions;

use App\Models\Vendor;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreVendorAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $vendor = new Vendor($this->attributes);
        
        $vendor->save();

        return $vendor;
    }
}
