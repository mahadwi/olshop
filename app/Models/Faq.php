<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Faq extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'title_en',
        'section',
        'section_en',
        'description',
        'description_en',
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (Faq $faq) {
            //also delete file if exist
            $imageFile = public_path('image/'.$faq->image);
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
