<?php

namespace App\Actions;

use App\Models\Faq;

class UpdateFaqAction
{
    private $faq;
    private $attributes;


    public function __construct(Faq $faq, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->faq = $faq;
    }

    public function handle()
    {
        $this->faq->fill($this->attributes)->save();

        return $this->faq;
    }
}
