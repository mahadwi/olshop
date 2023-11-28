<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'time_start',
        'time_end',
        'place',
        'maps',
        'detail_maps',
        'cover',
        'banner',        
    ];

    protected $casts = [
        'start_date' => 'date:d-m-Y',
        'end_date' => 'date:d-m-Y',
    ];


    protected $appends = ['banner_url', 'cover_url'];

    protected static function booted(): void
    {
        static::deleted(function (Event $event) {
            
            //also delete file if exist
            $banner = public_path('image/event/'.$event->banner);
            $cover = public_path('image/event/'.$event->cover);

            if(File::exists($banner) || File::exists($cover)){                
                //delete file
                File::delete($banner);
                File::delete($cover);
            }                
        });
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d');
    }    

    public function getBannerUrlAttribute()
    {
        return asset('image/event/'.$this->banner);
    }

    public function getCoverUrlAttribute()
    {
        return asset('image/event/'.$this->cover);
    }

    public function details()
    {
        return $this->hasMany(EventDetail::class);
    }

}
