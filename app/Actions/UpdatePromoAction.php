<?php

namespace App\Actions;

use App\Models\Promo;

class UpdatePromoAction
{
    private $attributes;

    public function __construct(Promo $promo, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->promo = $promo;
    }

    public function handle()
    {
        $this->promo->fill($this->attributes)->save();
        return $this->promo;
    }
}
