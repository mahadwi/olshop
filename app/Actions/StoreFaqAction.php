<?php

namespace App\Actions;

use App\Models\FaqQuestionAnswer;

class StoreFaqAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        $faq = new FaqQuestionAnswer($this->attributes);
        $faq->save();

        return $faq;
    }
}
