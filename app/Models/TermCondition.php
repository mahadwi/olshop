<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class TermCondition extends Model
{
    use HasFactory;

    protected $table = 'term_condition';

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
        static::deleted(function (TermCondition $returnPolice) {
            //also delete file if exist
            $imageFile = public_path('image/'.$returnPolice->image);
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
