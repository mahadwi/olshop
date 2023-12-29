<?php

namespace App\Transformers\API;

use App\Models\FaqSection;
use League\Fractal\TransformerAbstract;

class FaqSectionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'faqs'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [

    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FaqSection $faqSection)
    {
        return [
            'id' => $faqSection->id,
            'section' => $faqSection->section,
            'section_en' => $faqSection->section_en,
        ];
    }

    public function includeFaqs($faqSection)
    {
        $question = $faqSection->faqQuestionAnswer;
        return $this->collection($question, new FaqQuestionTransformer);
    }

}
