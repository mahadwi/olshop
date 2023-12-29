<?php

namespace App\Transformers\API;

use App\Models\FaqQuestionAnswer;
use League\Fractal\TransformerAbstract;

class FaqQuestionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(FaqQuestionAnswer $faqQuestion)
    {
        return [
            'id'                => $faqQuestion->id,
            'question'          => $faqQuestion->title,
            'question_en'       => $faqQuestion->title_en,
            'answer'            => $faqQuestion->description,
            'answer_en'         => $faqQuestion->description_en,
        ];
    }

}
