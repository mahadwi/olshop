<?php

namespace App\Actions;

use App\Models\DeliveryShipping;
use App\Services\File\UploadService;
use Illuminate\Support\Facades\File;


class UpdateDeliveryShippingAction
{
    private $termCondition;
    private $attributes;


    public function __construct(DeliveryShipping $deliveryShipping, array $attributes = [])
    {
        $this->attributes = $attributes;
        $this->deliveryShipping = $deliveryShipping;
    }

    public function handle()
    {
        if($this->attributes['image']){

            $oldFile = public_path('image/'.$this->deliveryShipping->image);

            if(File::exists($oldFile)){
                //delete file
                File::delete($oldFile);
            }

            $file = (new UploadService())->saveFile($this->attributes['image']);

            $this->attributes['image'] = $file['name'];

        } else {
            unset($this->attributes['image']);
        }

        $this->deliveryShipping->fill($this->attributes)->save();

        return $this->deliveryShipping;
    }
}
