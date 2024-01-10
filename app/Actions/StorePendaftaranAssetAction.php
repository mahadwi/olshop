<?php

namespace App\Actions;

use App\Models\PendaftaranAsset;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePenyusutanAssetAction;
use Symfony\Component\HttpFoundation\File\File;

class StorePendaftaranAssetAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $model = new PendaftaranAsset($this->attributes);
        
            $model->save();

            foreach($this->attributes['dataPenyusutan'] as $penyusutan){
                dispatch_sync(new StorePenyusutanAssetAction($penyusutan, $model));
            }

            return $model;
        });
        
    }
}
