<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreOrderApiAction;
use App\Actions\SetCompletedOrderAction;
use App\Http\Requests\API\OrderApiRequest;
use App\Http\Requests\API\SetCompletedOrderRequest;
use App\Transformers\API\OrderTransformer;
use App\Http\Requests\API\StoreOrderApiRequest;

class OrderApiController extends Controller
{

    public function index(OrderApiRequest $request)
    {
        $data = Order::where('user_id', auth()->user()->id)
        ->when($request->has('status'), function ($query) use ($request) {
            $query->where('status', $request->status);       
        })
        ->when($request->has('payment_status'), function ($query) use ($request) {
            $query->whereHas('paymentable', function ($query) use ($request){
                $query->where('status', $request->payment_status);
            });            
        })
        ->when($request->has('is_offline'), function ($query) use ($request) {
            $query->where('is_offline', $request->is_offline);       
        })
        ->orderByDesc('id')
        ->get();

        $order = fractal($data, new OrderTransformer)->toArray();

        return $this->apiSuccess($order);
    }

    public function show($order)
    {
        $order = Order::find($order);
        
        if($order){
            
            $order = fractal($order, new OrderTransformer());

            return $this->apiSuccess($order);
        } else {
            
            return $this->apiError([], [], 'Order Not Found');
        }

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

    public function setCompleted(SetCompletedOrderRequest $request, Order $order)
    {                
        //store order
        (new SetCompletedOrderAction($order))->handle();
        
        return $this->apiSuccess();
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

        foreach($details as $detail){
            $product = Product::find($detail['product_id']);

            if($product->stock < $detail['qty']) return "Product {$product['name']} Out Of Stock";
        }


        if($fixTotal != $request->total){
            return 'Total Invalid';
        }

        
        return false;
            
    }
}
