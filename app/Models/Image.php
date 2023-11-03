<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'imageable_id',
        'imageable_type',
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        // static::deleted(function (Image $image) {
        //     //also delete file if exist
        //     $imageFile = public_path('image/'.$image->name);
        //     if(File::exists($imageFile)){                
        //         //delete file
        //         File::delete($imageFile);
        //     }                
        // });
    }
}
