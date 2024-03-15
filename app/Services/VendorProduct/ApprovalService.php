<?php

namespace App\Services\VendorProduct;

use Carbon\Carbon;
use App\Models\Agreement;
use App\Helpers\AppHelper;
use App\Models\VendorAgreement;
use App\Constants\AgreementFileType;
use Illuminate\Support\Facades\File;
use App\Constants\VendorProductStatus;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use NcJoes\OfficeConverter\OfficeConverter;

class ApprovalService {

    private $path;
    private $copy_path;

    public function __construct()
    {
        $this->path = public_path('file');
        $this->copy_path = public_path('tmp');
    }

    public function draftFile($product, $type, $lang)
    {

        $uploadDraft = $this->isUploadDraft($type);
                
        if($uploadDraft && $uploadDraft[$lang]){
            
            $file = $this->getNameFile($uploadDraft[$lang], $product->id);
            if(!File::exists($this->path.'/'.$file)){
                $this->generatePdf($product, $uploadDraft[$lang], $lang); 
            } 

            return asset('file/'.$file);
            
        } 

        return null;
    }

    public function generatePdf($product, $file, $lang)
    {
        $sourceFile = $this->path.'/'.$file;
        $copyFile = $this->copy_path.'/'.$file;

        if(File::exists($sourceFile)){

            //copy file
            File::copy($sourceFile, $copyFile);
            $date = AppHelper::dateIndo(date('Y-m-d'));
            $dateEn = AppHelper::dateEnglish(date('Y-m-d'));
            //readfile
            $template = new TemplateProcessor($copyFile);

            if($lang == 'file'){
                $template->setValue('day', $date['day']);
                $template->setValue('date', $date['date']);
                $template->setValue('month', $date['month']);
                $template->setValue('year', $date['year']);

                $condition = 'Baru';
                if($product->condition == 'Like New') $condition = 'Second';
                $template->setValue('product_condition', $condition);
                $template->setValue('product_description', $product->description);

            } else {
                $template->setValue('day', $dateEn['day']);
                $template->setValue('date', $dateEn['date']);
                $template->setValue('month', $dateEn['month']);
                $template->setValue('year', $dateEn['year']);
                
                $template->setValue('product_condition', $product->condition);
                $template->setValue('product_description', $product->description_en);

            }
            
            //profile
            $template->setValue('profile_pt_name', AppHelper::profile()->pt_name);
            $template->setValue('profile_phone', AppHelper::profile()->phone);
            $template->setValue('profile_address', AppHelper::profile()->address);

            //consignor
            $template->setValue('consignor_name', $product->vendor->name);
            $template->setValue('consignor_nik', $product->vendor->ktp);
            $template->setValue('consignor_address', $product->vendor->address);
            $template->setValue('consignor_phone', $product->vendor->phone);

            //product
            $template->setValue('product_name', $product->name);
            $template->setValue('product_brand', $product->brand->name);
            $template->setValue('product_category', $product->productCategory->name);
            $template->setValue('product_color', $product->color->name);

            $template->setValue('product_weight', $product->weight);
            $template->setValue('product_width', $product->width);
            $template->setValue('product_length', $product->length);
            $template->setValue('product_height', $product->height);

            $template->setValue('product_sale_price', AppHelper::priceFormat($product->sale_price));
            $template->setValue('product_sale_price_usd', AppHelper::priceFormat($product->sale_usd));
            $template->setValue('product_approve_date', Carbon::parse($product->approve_date)->format('d-m-Y'));            
            $template->setValue('deadline', $product->deadline);

            //consignment form
            $template->setValue('bank_name', AppHelper::profile()->bank);
            $template->setValue('bank_rekening', AppHelper::profile()->bank_account_number);

            
            $template->saveAs($copyFile);

            $converter = new OfficeConverter($copyFile, $this->path);
            $converter->convertTo($this->getNameFile($file, $product->id));
        }
    }    

    public function getNameFile($file, $product_id)
    {
       return File::name($file).'_'.$product_id.'_draft.pdf' ;
    }

    public function isUploadDraft($type)
    {
        return Agreement::where('file_type', $type)->first();
    }
    

}