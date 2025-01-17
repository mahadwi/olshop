<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class ConsignmentCard extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'consignment_detail_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'icon',
        'loadmore_type',
        'loadmore_link',
        'loadmore_text'
    ];

    protected $appends = ['icon_url'];

    protected static function booted(): void
    {
        static::deleted(function (ConsignmentDetail $consignmentDetail) {
            //also delete file if exist
            $imageFile = public_path('image/consignment/'.$consignmentDetail->icon);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getIconUrlAttribute()
    {
        return asset('image/consignment/'.$this->icon);
    }

    public function consignmentDetail() {
        return $this->belongsTo(ConsignmentDetail::class);
    }
}
