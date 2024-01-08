<?php

namespace App\Actions;

use App\Models\PembelianAsset;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\File;

class StorePembelianAssetAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $model = new PembelianAsset($this->attributes);
        
            $model->save();

            return $model;
        });
        
    }
}
