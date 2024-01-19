<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'copyright',
        'logo',
        'subdistrict_id',
    ];

    protected $hidden = ['created_at', 'updated_at'];

    protected $appends = ['logo_url'];

    protected static function booted(): void
    {
        static::deleted(function (Profile $profile) {
            //also delete file if exist
            $imageFile = public_path('image/'.$profile->logo);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function getLogoUrlAttribute()
    {
        return asset('image/'.$this->logo);
    }
}
