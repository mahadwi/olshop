<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class PrivacyPolice extends Model
{
    use HasFactory;

    protected $table = 'privacy_police';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'description',
        'cp',
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (PrivacyPolice $privacyPolice) {
            //also delete file if exist
            $imageFile = public_path('image/'.$privacyPolice->image);
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
