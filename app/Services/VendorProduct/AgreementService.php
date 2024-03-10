<?php

namespace App\Services\VendorProduct;

use App\Constants\AgreementFileType;
use App\Models\Agreement;
use App\Models\VendorAgreement;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\TemplateProcessor;
use NcJoes\OfficeConverter\OfficeConverter;

class AgreementService
{
    private $path;

    public function __construct()
    {
        $this->path = public_path('file');
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
                    $this->generatePdf($product, $file->file);
                }

                if($file->file_en){
                    $this->generatePdf($product, $file->file_en);
                }
            }
        }
        
    }

    public function generatePdf($product, $file)
    {
        $sourceFile = $this->path.'/'.$file;
        if(File::exists($sourceFile)){

            //readfile
            $template = new TemplateProcessor($sourceFile);
            $template->setValue('name', $product->vendor->name);
            $template->setValue('nik', $product->vendor->ktp);
            
            $template->saveAs($sourceFile);

            $converter = new OfficeConverter($sourceFile, $this->path);
            $converter->convertTo(File::name($file).'_'.$product->id.'_draft.pdf');
        }
    }    

    
}

