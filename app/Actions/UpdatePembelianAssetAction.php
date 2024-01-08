<?php

namespace App\Actions;

use App\Models\PembelianAsset;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdatePembelianAssetAction
{
    private $model;
    private $attributes;


    public function __construct(PembelianAsset $model, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->model = $model;
    }

    public function handle()
    {   
        $this->model->fill($this->attributes)->save();

        return $this->model;
    }
}
