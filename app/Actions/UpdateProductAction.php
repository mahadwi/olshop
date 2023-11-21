<?php

namespace App\Actions;

use App\Models\Product;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateProductAction
{
    private $product;
    private $attributes;


    public function __construct(Product $product, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->product = $product;
    }

    public function handle()
    {   
        $this->product->fill($this->attributes)->save();

        return $this->product;
    }
}
