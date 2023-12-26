<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqImage extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (FaqImage $faqImage) {
            //also delete file if exist
            $imageFile = public_path('image/faq/'.$faqImage->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return asset('image/faq/'.$this->image);
    }
}
