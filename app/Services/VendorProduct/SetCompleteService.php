<?php

namespace App\Services\VendorProduct;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Agreement;
use App\Models\VendorProduct;
use App\Models\VendorAgreement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Constants\VendorProductStatus;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Actions\UpdateVendorProductAction;
use App\Models\ProductImage;
use NcJoes\OfficeConverter\OfficeConverter;

class SetCompleteService
{
    public function handle($id)
    {        
        $product = VendorProduct::where('id', $id)->first();        
        
        return DB::transaction(function () use ($product) {

            $param = ['status' => VendorProductStatus::COMPLETED];

            dispatch_sync(new UpdateVendorProductAction($product, $param));   

            $paramProduct = $product->makeHidden(
                ['id','entry_date','confirm_date', 'created_at', 'updated_at', 'product_deadline', 
                'approve_date', 'note', 'status']
            )->toArray();

            $merge = [
                'entry_date'        => date('d-m-Y'),
                'expired_date'      => Carbon::parse($product->product_deadline)->format('d-m-Y'),
                'stock'             => 1,
                'vendor_product_id' => $product->id
            ];

            $paramProduct = [...$paramProduct, ...$merge];            

            $newProduct = new Product($paramProduct);
        
            $newProduct->save();

            $this->moveImage($product, $newProduct);
        });
    }

    public function moveImage($odlProduct, $newProduct)
    {
        if($odlProduct->imageable){
            $oldPath = public_path('image/');
            $newPath = public_path('image/product/');

            foreach($odlProduct->imageable as $image){
                $old = $oldPath.$image->name;
                $new = $newPath.$image->name;

                //copy image
                if(File::exists($old)){
                    File::copy($old, $new);                    
                }                

                // store to product image
                $paramImage = [
                    'product_id' => $newProduct->id,
                    'name'       => $image->name
                ];
                $storeImage = new ProductImage($paramImage);        
                $storeImage->save();

            }
            
        }
    }

}

