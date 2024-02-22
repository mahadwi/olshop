<?php

namespace App\Actions\API;

use App\Models\Order;
use App\Models\OrderReview;
use App\Actions\StoreImageAction;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use App\Services\File\UploadService;

class StoreOrderReviewAction
{
    private $attributes;

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function handle()
    {
        return DB::transaction(function () {            

            foreach($this->attributes['reviews'] as $dataInsert){

                $orderDetail = OrderDetail::find($dataInsert['order_detail_id']);

                $review = new OrderReview($dataInsert);
                $review->user_id = $this->attributes['user_id'];
                $review->product_id = $orderDetail->product_id;
                $review->save();
                
                if(isset($dataInsert['image'])){
                    //upload gambar
                    foreach($dataInsert['image'] as $image){
                        $file = (new UploadService())->saveFile($image, 'review');
                        
                        $attributes = ['name' => $file['name']];
    
                        dispatch_sync(new StoreImageAction($attributes, $review));
    
                    }
                }

            }
            
            $order = Order::find($this->attributes['order_id']);

            return $order;
        });
    }
}