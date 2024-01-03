<?php

namespace App\Http\Controllers\API;

use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreOrderApiAction;
use App\Transformers\API\OrderTransformer;
use App\Http\Requests\API\StoreOrderApiRequest;
use App\Models\Order;

class OrderApiController extends Controller
{

    public function index()
    {
        $data = Order::where('user_id', auth()->user()->id)->get();

        $order = fractal($data, new OrderTransformer)->toArray();

        return $this->apiSuccess($order);
    }

    public function store(StoreOrderApiRequest $request)
    {
        $validate = $this->validateOrder($request);

        if($validate) return $this->apiError([], [], $validate);
        
        //store order
        $order = (new StoreOrderApiAction($request->all()))->handle();
        
        $order = fractal($order, new OrderTransformer);           
            
        return $this->apiSuccess($order);
    }

    public function validateOrder($request)
    {        

        $details = collect($request['details']);

        $totalDetail = $details->sum('total');        

        $fixTotal = ($totalDetail + $request->ongkir);

        if($request->voucher){

            $voucher = Voucher::where('code', $request->voucher)
            ->where('use_for', VoucherUseFor::PRODUCT)->first();

            if(!$voucher){
                return 'Voucher Use For Invalid';
            }

            //check type voucher
            if($voucher->type == VoucherType::B1G1){
                
                // if($request->qty < 2) return 'Qty minimal is 2';
                
                // $subtract = $price;

            } else if($voucher->type == VoucherType::PERCENT){
                $subtract = ($voucher->disc_percent / 100) * $fixTotal;
            } else if($voucher->type == VoucherType::PRICE){
                $subtract = $voucher->disc_price;
            }

            $fixTotal -= $subtract;            
            
        }
        
        if($fixTotal != $request->total){
            return 'Total Invalid';
        }

        return false;
            
    }
}
