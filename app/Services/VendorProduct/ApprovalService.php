<?php

namespace App\Services\VendorProduct;

use App\Models\Agreement;
use App\Models\VendorAgreement;
use App\Constants\AgreementFileType;
use Illuminate\Support\Facades\File;
use App\Constants\VendorProductStatus;
use PhpOffice\PhpWord\TemplateProcessor;
use NcJoes\OfficeConverter\OfficeConverter;

class ApprovalService {

    private $path;

    public function __construct()
    {
        $this->path = public_path('file');
    }

    public function draftFile($product, $type, $lang)
    {

        $uploadDraft = $this->isUploadDraft($type);
                
        if($uploadDraft && $uploadDraft[$lang]){
            
            $file = $this->getNameFile($uploadDraft[$lang], $product->id);
            if(!File::exists($this->path.'/'.$file)){
                $this->generatePdf($product, $uploadDraft[$lang]); 
            } 

            return asset('file/'.$file);
            
        } 

        return null;
    }

    public function generatePdf($product, $file)
    {
        $sourceFile = $this->path.'/'.$file;
        if(File::exists($sourceFile)){

            //readfile
            $template = new TemplateProcessor($sourceFile);
            // $template->setValue('name', $product->vendor->name);
            // $template->setValue('nik', $product->vendor->ktp);
            
            $template->saveAs($sourceFile);

            $converter = new OfficeConverter($sourceFile, $this->path);
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