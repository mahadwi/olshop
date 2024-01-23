<?php

namespace App\Services\File;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class UploadService
{
    protected $rootPath;

    public function __construct()
    {
        $this->rootPath = public_path('image');
    }

    public function saveFile($file, $path = '')
    {        
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = '/'.$path.'/';

        $originalPath = $path != '//' ? $this->rootPath.$path : $this->rootPath;

        if (!file_exists($originalPath)) {
            if (!mkdir($originalPath, 0777, true) && !is_dir($originalPath)) {
                throw new Exception(sprintf('Directory "%s" was not created', $originalPath));
            }
        }

        $file->move($originalPath, $filename);

        return [
            'name' => $filename,
            'path' => $path,
            'base_path' => $this->rootPath,
            'full_path' => $originalPath.$filename,
        ];
    }

    public function uploadFile($file, $path = '')
    {        
        $this->rootPath = public_path('file');

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = '/'.$path.'/';

        $originalPath = $path != '//' ? $this->rootPath.$path : $this->rootPath;

        if (!file_exists($originalPath)) {
            if (!mkdir($originalPath, 0777, true) && !is_dir($originalPath)) {
                throw new Exception(sprintf('Directory "%s" was not created', $originalPath));
            }
        }

        $file->move($originalPath, $filename);

        return [
            'name' => $filename,
            'path' => $path,
            'base_path' => $this->rootPath,
            'full_path' => $originalPath.$filename,
        ];
    }

    public function deleteFile($images, $path = '')
    {        
        foreach($images as $image){
            //cek path file
            $path = '/'.$path.'/';

            $originalPath = $path != '//' ? $this->rootPath.$path : $this->rootPath.'/';

            $imageFile = $originalPath.$image->name;
            if(File::exists($imageFile)){   
                //delete file
                File::delete($imageFile);
            }      
        }
    }

}