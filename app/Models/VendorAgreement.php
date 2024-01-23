<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorAgreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'vendor_product_id',        
        'agreement_id',
        'file',
        'is_approved',
        'note',
    ];

    protected $appends = ['draft', 'file_url'];


    public function agreement()
    {
        return $this->belongsTo(Agreement::class)->orderBy('id');
    }

    public function vendorProduct()
    {
        return $this->belongsTo(VendorProduct::class);
    }


    public function getDraftAttribute()
    {
        if(!$this->agreement->file){
            return null;
        }

        $filename = File::name($this->agreement->file);

        return asset('file/'.$filename.'_'.$this->vendor_product_id.'_draft.pdf');
    }

    public function getFileUrlAttribute()
    {
        if(!$this->file){
            return null;
        }

        return asset('file/'.$this->file);
    }
}
