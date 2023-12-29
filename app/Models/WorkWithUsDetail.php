<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class WorkWithUsDetail extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'work_with_us_id',
        'section',
        'title',
        'title_en',
        'description',
        'description_en',
        'image',
        'link',
    ];

    protected $appends = ['image_url'];

    protected static function booted(): void
    {
        static::deleted(function (WorkWithUsDetail $workWithUsDetail) {
            //also delete file if exist
            $imageFile = public_path('image/workWithUs/'.$workWithUsDetail->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return asset('image/workWithUs/'.$this->image);
    }

    public function workWithUs() {
        return $this->belongsTo(WorkWithUs::class);
    }

    public function workWithUsCard() {
        return $this->hasMany(WorkWithUsCard::class);
    }
}
