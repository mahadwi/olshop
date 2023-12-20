<?php

namespace App\Actions\API;

use App\Models\Address;
use App\Models\User;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;

class UpdateAddressApiAction
{
    private $address;
    private $attributes;


    public function __construct(Address $address, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->address = $address;
    }

    public function handle()
    {
        if($this->attributes['is_primary']){

            $address = $this->address->user->addresses;
            if($address->count() > 1){
                $update = $this->address->user->addresses()->where('id', '!=', $this->address->id)->get();   
                $update->each(function ($address) {
                    $address->update(['is_primary' => false]);
                });
            }
            
        }        
        $this->address->fill($this->attributes)->save();

        return $this->address;
    }
}
