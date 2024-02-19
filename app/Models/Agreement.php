<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'file',        
        'is_active',
        'file_type'
    ];

    protected $appends = ['status'];

    protected static function booted(): void
    {
        static::deleted(function (Agreement $agreement) {
            //also delete file if exist
            $file = public_path('file/'.$agreement->file);
            if(File::exists($file)){
                //delete file
                File::delete($file);
            }
        });
    }

    function getStatusAttribute() {
        return $this->is_active ? __('app.label.active') : __('app.label.not_active');
    }
}
