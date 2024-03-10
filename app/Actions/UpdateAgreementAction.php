<?php

namespace App\Actions;

use App\Models\Agreement;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateAgreementAction
{
    private $agreement;
    private $attributes;

    public function __construct(Agreement $agreement, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->agreement = $agreement;
    }

    public function handle()
    {   

        $fileKeys = array_keys(array_filter([
            'file' => $this->attributes['file'],
            'file_en' => $this->attributes['file_en'],
        ], 'is_file')); // Use a built-in function for existence and file type check

        foreach ($fileKeys as $fileKey) {
            $filePath = $this->attributes[$fileKey];

            $oldPath = $this->agreement[$fileKey];

            $oldFile = public_path('file/' . $oldPath);
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
        
            // Upload and update attribute directly within the loop
            $this->attributes[$fileKey] = (new UploadService())->uploadFile($filePath)['name'];

        }

        
        // Remove null values
        unset($this->attributes[array_search(null, $this->attributes, true)]);
        
        $this->agreement->fill($this->attributes)->save();
        
        return $this->agreement;
    }
}