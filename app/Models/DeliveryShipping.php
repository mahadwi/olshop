<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class DeliveryShipping extends Model
{
    use HasFactory;

    protected $table = 'delivery_shipping';

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'title',
        'description',
        'description_en',
        'image',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (DeliveryShipping $deliveryShipping) {
            //also delete file if exist
            $imageFile = public_path('image/'.$deliveryShipping->image);
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
