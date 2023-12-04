<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Review extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'image',
        'rating',
        'review',
        'is_display',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (Review $review) {
            //also delete file if exist
            $imageFile = public_path('image/'.$review->image);
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
