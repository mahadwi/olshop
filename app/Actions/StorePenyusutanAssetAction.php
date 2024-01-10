<?php

namespace App\Actions;

use App\Models\PenyusutanAsset;

class StorePenyusutanAssetAction
{
    private $attributes;
    private $model;

    public function __construct(array $attributes = [], $model)
    {
        $this->attributes = $attributes;
        $this->model = $model;
    }

    public function handle()
    {
        $penyusutan = new PenyusutanAsset($this->attributes);
        $penyusutan->pendaftaranAsset()->associate($this->model)->save();

        return $penyusutan;

    }
}
