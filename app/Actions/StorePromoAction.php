<?php

namespace App\Actions;

use App\Models\Promo;

class StorePromoAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $promo = new Promo($this->attributes);
        $promo->save();

        return $promo;
    }
}
