<?php

namespace App\Actions;

use App\Models\PenjualanAsset;
use Illuminate\Support\Facades\DB;
use App\Actions\StorePenyusutanAssetAction;
use App\Models\PenyusutanAsset;
use Symfony\Component\HttpFoundation\File\File;

class StorePenjualanAssetAction
{
    private $attributes;
    
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {

            $model = new PenjualanAsset($this->attributes);
        
            $model->save();

            $year = substr($this->attributes['tanggal'], 6, 4);
            $month = substr($this->attributes['tanggal'], 3, 2);
            $date = $year . '-' . $month . '-01';
            $last_date = date('Y-m-t', strtotime($date));

            PenyusutanAsset::where('pendaftaran_asset_id', $this->attributes['pendaftaran_asset_id'])
            ->whereDate('tanggal', '>', $last_date)->delete();

            return $model;
        });
        
    }
}
