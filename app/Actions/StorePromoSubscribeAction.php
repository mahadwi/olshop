<?php

namespace App\Actions;

use App\Models\PromoSubscribe;

class StorePromoSubscribeAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $promoSubscribe = new PromoSubscribe($this->attributes);
        $promoSubscribe->save();

        return $promoSubscribe;
    }
}
