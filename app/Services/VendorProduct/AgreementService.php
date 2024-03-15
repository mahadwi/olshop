<?php

namespace App\Services\VendorProduct;

use Carbon\Carbon;
use App\Models\Agreement;
use App\Helpers\AppHelper;
use App\Models\VendorAgreement;
use App\Constants\AgreementFileType;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use NcJoes\OfficeConverter\OfficeConverter;

class AgreementService
{
    private $path;
    private $copy_path;

    public function __construct()
    {
        $this->path = public_path('file');
        $this->copy_path = public_path('tmp');
    }

    public function generate($product)
    {
        $files = Agreement::where('is_active', true)->where('file_type', AgreementFileType::AGREEMENT)->get();

        $param = [
            'vendor_id' => $product->vendor_id,  
            'vendor_product_id' => $product->id,  
        ];

        $exits = VendorAgreement::where($param);

        if($exits->get()->isNotEmpty()){
            $exits->delete();
        }
                
        if($files->isNotEmpty()){
            foreach($files as $file){
                $param['agreement_id'] = $file->id;

                $insert = new VendorAgreement($param);
        
                $insert->save();

                //generate file pdf
                if($file->file){
                    $this->generatePdf($product, $file->file, 'file');
                }

                if($file->file_en){
                    $this->generatePdf($product, $file->file_en, 'file_en');
                }
            }
        }
        
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


            } else {
                $template->setValue('day', $dateEn['day']);
                $template->setValue('date', $dateEn['date']);
                $template->setValue('month', $dateEn['month']);
                $template->setValue('year', $dateEn['year']);

            }

            
            $template->setValue('profile_pt_name', AppHelper::profile()->pt_name);
            $template->setValue('profile_phone', AppHelper::profile()->phone);
            $template->setValue('profile_address', AppHelper::profile()->address);

            $template->setValue('consignor_name', $product->vendor->name);
            $template->setValue('consignor_nik', $product->vendor->ktp);
            $template->setValue('consignor_address', $product->vendor->address);

            $template->setValue('product_name', $product->name);
            $template->setValue('product_brand', $product->brand->name);
            $template->setValue('product_category', $product->productCategory->name);
            $template->setValue('product_color', $product->color->name);
            $template->setValue('product_deadline', $product->product_deadline);
            $template->setValue('product_price', AppHelper::priceFormat($product->price));
            $template->setValue('product_price_usd', AppHelper::priceFormat($product->price_usd));
            $template->setValue('product_sale_price', AppHelper::priceFormat($product->sale_price));
            $template->setValue('product_sale_price_usd', AppHelper::priceFormat($product->sale_usd));
            $template->setValue('product_commission_type', $product->commission_type);
            $template->setValue('product_commission', $product->commission);

            $template->setValue('bank_name', AppHelper::profile()->bank);
            $template->setValue('bank_rekening', AppHelper::profile()->bank_account_number);
            $template->setValue('bank_holder', AppHelper::profile()->bank_account_holder);

            $template->setValue('deadline', $product->deadline);
            

            
            $template->saveAs($copyFile);

            $converter = new OfficeConverter($copyFile, $this->path);

            $converter->convertTo(File::name($file).'_'.$product->id.'_draft.pdf');
        }
    }    

    
}

