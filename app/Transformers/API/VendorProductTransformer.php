<?php

namespace App\Transformers\API;

use stdClass;
use App\Models\Agreement;
use App\Models\VendorProduct;
use App\Constants\AgreementFileType;
use App\Constants\VendorProductStatus;
use League\Fractal\TransformerAbstract;
use App\Services\VendorProduct\ApprovalService;

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
            'price_usd'         => $product->price_usd,
            'sale_price'        => $product->sale_price,
            'sale_usd'          => $product->sale_usd,
            'consignment_price' => $product->sale_price * 0.01,
            'consignment_usd'   => $product->sale_usd * 0.01,
            'commission_type'   => $product->commission_type,
            'commission'        => $product->commission,
            'condition'         => $product->condition,
            'weight'            => $product->weight,
            'height'            => $product->height,
            'width'             => $product->width,
            'length'            => $product->length,
            'status'            => $product->status,
            'note'              => $product->note,
            'offered_date'      => $product->created_at->format(config('app.default.datetime_human')),
            'confirm_date'      => $product->confirm_date ? $product->confirm_date->format('d-m-Y') : null,
            'images'            => $this->images($product),
            'approve_file'      => $this->approveFile($product),
            'cancel_file'       => $this->cancelFile($product),
            'consignment_file'  => $this->consignmentFile($product),
            'product_deadline'  => $product->product_deadline ? $product->product_deadline->format('d-m-Y') : null,
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
        $file = new stdClass();
        $name = Agreement::where('file_type', AgreementFileType::CANCEL)->first();

        $file->name = null;
            
        if($product->status == VendorProductStatus::NOT_APPROVED || $product->status == VendorProductStatus::CANCELED){
            $file->name = $name ? $name->name : null;
            $file->draft = (new ApprovalService())->draftFile($product, AgreementFileType::CANCEL);   
        }  else {
            $file->draft = null;
        }

        $file->cancel_file = $product->cancel_file_url;

        $file->status = null;

        if($product->status == VendorProductStatus::NOT_APPROVED && $product->cancel_file){
            $file->status = 'Review';
        }

        if($product->status == VendorProductStatus::CANCELED){
            $file->status = 'Approved';
        }

        return $file;

    }

    public function approveFile($product)
    {
        $file = new stdClass();
        $name = Agreement::where('file_type', AgreementFileType::APPROVAL)->first();

        $file->name = null;
            
        if(($product->confirm_date && $product->status == VendorProductStatus::REVIEW) || ($product->confirm_date && $product->status == VendorProductStatus::APPROVED) || ($product->confirm_date && $product->status == VendorProductStatus::COMPLETED)){
            $file->name = $name ? $name->name : null;
            $file->draft = (new ApprovalService())->draftFile($product, AgreementFileType::APPROVAL);   
        }  else {
            $file->draft = null;
        }

        $file->approve_file = $product->approve_file_url;

        $file->status = null;

        if($product->status == VendorProductStatus::REVIEW && $product->approve_file){
            $file->status = 'Review';
        }

        if($product->status == VendorProductStatus::APPROVED || $product->status == VendorProductStatus::COMPLETED){
            $file->status = 'Approved';
        }

        return $file;


    }

    public function consignmentFile($product)
    {
        
        if(($product->status == VendorProductStatus::COMPLETED)){
            return (new ApprovalService())->draftFile($product, AgreementFileType::CONSIGNMENT);   
        }  

        return null;


    }

}
