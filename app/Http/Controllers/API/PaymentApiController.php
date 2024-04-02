<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Http\Controllers\Controller;
use App\Actions\API\StorePaymentApiAction;
use App\Transformers\API\OrderPosTransformer;
use App\Http\Requests\API\StorePaymentApiRequest;

class PaymentApiController extends Controller
{
    public function store(StorePaymentApiRequest $request)
    {
        $validate = $this->validateOrder($request);
        if($validate) return $this->apiError([], [], $validate);

        //store order
        $order = (new StorePaymentApiAction($request->all()))->handle();
        
        $order = fractal($order, new OrderPosTransformer);           
            
        return $this->apiSuccess($order);
    }

    public function validateOrder($request)
    {        
        if($request->order->courier != 'pickup' || !$request->order->is_offline){
            return 'Invalid Order';
        }

        $details = $request->order->orderDetail;

        $totalDetail = $details->sum('total');        

        $fixTotal = $totalDetail;

        foreach($details as $detail){

            $product = $detail->product;

            if($product->stock < $detail->qty) return "Product {$product->name} Out Of Stock";
        }

        
        if($fixTotal != $request->jumlah){
            return 'Total Invalid';
        }

        
        return false;
            
    }
}
