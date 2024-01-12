<?php

namespace App\Actions;

use App\Models\Customer;
use App\Services\File\UploadService;
use Symfony\Component\HttpFoundation\File\File;

class StoreCustomerAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $model = new Customer($this->attributes);
        
        $model->save();

        return $model;
    }
}
