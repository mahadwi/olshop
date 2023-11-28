<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscribeSplash extends Model
{
    use HasFactory;

    protected $table = 'subscribe_splash';

    protected $fillable = [
        'title',
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (SubscribeSplash $subscribeSplash) {
            //also delete file if exist
            $imageFile = public_path('image/subscribe/'.$subscribeSplash->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return asset('image/subscribe/'.$this->image);
    }
}
