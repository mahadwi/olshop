<?php

namespace App\Actions;

use App\Constants\VendorProductStatus;
use App\Models\VendorProduct;
use App\Services\VendorProduct\AgreementService;
use Illuminate\Support\Facades\DB;

class UpdateVendorProductAction
{
    private $vendorProduct;
    private $attributes;

    public function __construct(VendorProduct $vendorProduct, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->vendorProduct = $vendorProduct;
    }

    public function handle()
    {
        return DB::transaction(function () {

            if(isset($this->attributes['update_status']) && $this->attributes['update_status']){

                $this->attributes['status'] = $this->attributes['update_status'];

                if($this->attributes['status'] == VendorProductStatus::APPROVED){

                    $this->attributes['approve_date'] = date('Y-m-d');                

                    (new AgreementService())->generate($this->vendorProduct->load('vendor'));     
                }
            } else {

                if($this->vendorProduct->status == VendorProductStatus::NOT_APPROVED){
                    $this->attributes['status'] = VendorProductStatus::REVIEW;
                }
            }        
            
            $this->vendorProduct->fill($this->attributes)->save();

            return $this->vendorProduct;
        });
    }
}
