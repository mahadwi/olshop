<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class AuthenticationDetail extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'authentication_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'image',
        'section'
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (AuthenticationDetail $authenticationDetail) {
            //also delete file if exist
            $imageFile = public_path('image/'.$authenticationDetail->image);
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

    public function authentication() {
        return $this->belongsTo(Authentication::class);
    }
}
