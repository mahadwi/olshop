<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekening extends Model
{
    use HasFactory;

    protected $fillable = [        
        'bank',
        'bank_account_holder',
        'bank_account_number',
        'is_active',
        'logo'
    ];

    protected $appends = ['logo_url'];

    protected static function booted(): void
    {
        static::deleted(function (Rekening $rekening) {
            //also delete file if exist
            $imageFile = public_path('image/'.$rekening->logo);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('image/'.$this->logo) : null;
    }
}
