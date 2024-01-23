<?php

namespace App\Transformers\API;

use App\Models\VendorAgreement;
use League\Fractal\TransformerAbstract;

class VendorAgreementTransformer extends TransformerAbstract
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
    public function transform(VendorAgreement $agreement)
    {
        return [
            'id'            => $agreement->id,
            'name'          => $agreement->agreement->name,
            'draft'         => $agreement->draft,
            'file'          => $agreement->file_url,
            'is_approved'   => $agreement->is_approved,
            'note'          => $agreement->note,
        ];
    }
}
