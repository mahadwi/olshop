<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'description_en',
        'image',
    ];

    protected $appends = ['image_url', 'is_valid'];

    protected static function booted(): void
    {
        static::deleted(function (Brand $brand) {
            //also delete file if exist
            $imageFile = public_path('image/brand/'.$brand->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('image/brand/'.$this->image) : null;
    }

    public function getIsValidAttribute()
    {
        return $this->image && $this->description && $this->description_en ? true : false;
    }
}
