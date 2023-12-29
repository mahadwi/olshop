<?php

namespace App\Transformers\API;

use App\Models\FaqQuestionAnswer;
use App\Models\FaqSection;
use App\Models\FaqImage;
use League\Fractal\TransformerAbstract;

class FaqTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [

    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'faq_section',
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */

    public function transform(FaqImage $faqImage)
    {
        return
        [
            "image" => $faqImage->image_url,
        ];
    }

    public function includeFaqSection()
    {
        $faqSection = FaqSection::get();
        return $this->collection($faqSection, new FaqSectionTransformer);
    }
}
