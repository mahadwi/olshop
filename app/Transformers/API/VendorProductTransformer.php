<?php

namespace App\Transformers\API;

use App\Constants\VendorProductStatus;
use App\Models\VendorProduct;
use App\Services\VendorProduct\ApprovalService;
use League\Fractal\TransformerAbstract;

class VendorProductTransformer extends TransformerAbstract
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
    public function transform(VendorProduct $product)
    {
        return [
            'id'                => $product->id,
            'name'              => $product->name,
            'description'       => $product->description,
            'description_en'    => $product->description_en,
            'color'             => $product->color->name,
            'history'           => $product->history,
            'history_en'        => $product->history_en,
            'category'          => $product->productCategory->name,
            'brand'             => $product->brand->name,
            'price'             => $product->price,
            'price_usd'         => $product->price,
            'sale_price'        => $product->sale_price,
            'sale_usd'          => $product->sale_usd,
            'commission_type'   => $product->commission_type,
            'commission'        => $product->commission,
            'condition'         => $product->condition,
            'weight'            => $product->weight,
            'height'            => $product->height,
            'width'             => $product->width,
            'length'            => $product->length,
            'status'            => $product->status,
            'note'            => $product->note,
            'offered_date'      => $product->created_at->format(config('app.default.datetime_human')),
            'confirm_date'      => $product->confirm_date ? $product->confirm_date->format('d-m-Y') : null,
            'images'            => $this->images($product),
            'approve_file_draft'=> $this->approveFile($product),
            'approve_file'      => $product->approve_file_url,
            'cancel_file_draft' => $this->cancelFile($product),
            'cancel_file'       => $product->cancel_file_url,
        ];
    }

    private function images($product)
    {
        return is_null($product->imageable) ? [] : $product->imageable->map(function ($item) {
            return asset('image/'.$item->name);
        });
    }

    public function cancelFile($product)
    {
        if($product->status == VendorProductStatus::NOT_APPROVED || $product->status == VendorProductStatus::CANCELED){
            return (new ApprovalService())->draftFile($product, 'cancel');   
        } 

        return null;
    }

    public function approveFile($product)
    {
        if(($product->confirm_date && $product->status == VendorProductStatus::REVIEW) || ($product->confirm_date && $product->status == VendorProductStatus::APPROVED) || ($product->confirm_date && $product->status == VendorProductStatus::COMPLETED)){
            return (new ApprovalService())->draftFile($product, 'approve');   
        } 

        return null;
    }

}
