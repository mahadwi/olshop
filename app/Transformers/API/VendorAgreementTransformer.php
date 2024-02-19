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
            'name_en'          => $agreement->agreement->name_en,
            'draft'         => $agreement->draft,
            'file'          => $agreement->file_url,
            'status'        => $agreement->is_approved ? 'Approved' : 'Not Approved',
            'note'          => $agreement->note,
        ];
    }
}
