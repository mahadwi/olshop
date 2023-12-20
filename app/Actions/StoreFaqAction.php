<?php

namespace App\Actions;

use App\Models\Faq;

class StoreFaqAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $faq = new Faq($this->attributes);
        $faq->save();

        return $faq;
    }
}
