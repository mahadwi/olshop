<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AboutUs extends Model
{
    use HasFactory;

    protected $table = 'about_us';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'title_en',
        'description',
        'description_en',
        'address',
        'detail_address',
        'maps',
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (AboutUs $aboutUs) {
            //also delete file if exist
            $imageFile = public_path('image/'.$aboutUs->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return asset('image/'.$this->image);
    }
}
