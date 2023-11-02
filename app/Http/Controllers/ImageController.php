<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ImageController extends Controller
{
    public function getImage($productImage)
    {

        $filePath = public_path('image/'.$productImage);
        
        $headers = [
            'Content-Type' => 'image/png', // Set the appropriate content type for your file type
            'Content-Disposition' => 'inline; filename="my-file.png"',
        ];

        return response()->file($filePath, $headers);
    }

    public function deleteImage(Request $request)
    {        
        try {

            $filename = str_replace('"', '', $request->name);
            //cek file
            $cekFile = public_path('image/'.$filename);
            if(File::exists($cekFile)){                
                //delete file
                File::delete($cekFile);
            }   

            $image = Image::where('name', $filename)->first();
            $image->delete();

            return response()->json('success');

        } catch (\Throwable $th) {
            return response()->json('error');
        }
    }
}
