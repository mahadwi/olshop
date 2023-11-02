<?php

namespace App\Actions;

use App\Models\Image;
use Illuminate\Support\Facades\DB;

class StoreImageAction
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
        return DB::transaction(function () {
            $image = new Image($this->attributes);

            $image->imageable()->associate($this->model)->save();

            return $image;
        });
    }
}
