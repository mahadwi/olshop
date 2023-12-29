<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignmentDetail extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'consignment_id',
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
        static::deleted(function (ConsignmentDetail $consignmentDetail) {
            //also delete file if exist
            $imageFile = public_path('image/consignment/'.$consignmentDetail->image);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return asset('image/consignment/'.$this->image);
    }

    public function consignment() {
        return $this->belongsTo(Consignment::class);
    }

    public function consignmentCard() {
        return $this->hasMany(ConsignmentCard::class);
    }
}
