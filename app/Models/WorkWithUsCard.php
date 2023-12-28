<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkWithUsCard extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = [
        'work_with_us_detail_id',
        'title',
        'title_en',
        'description',
        'description_en',
        'icon',
    ];

    protected static function booted(): void
    {
        static::deleted(function (WorkWithUsCard $workWithUsCard) {
            //also delete file if exist
            $imageFile = public_path('image/workWithUs/'.$workWithUsCard->icon);
            dd($imageFile);
            if(File::exists($imageFile)){
                //delete file
                File::delete($imageFile);
            }
        });
    }

    public function getIconUrlAttribute()
    {
        return asset('image/workWithUs/'.$this->icon);
    }

    public function workWithUsDetail() {
        return $this->belongsTo(WorkWithUsDetail::class);
    }
}
