<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreOrderPosApiAction;
use App\Constants\PaymentState;
use App\Http\Requests\API\OrderPosApiRequest;
use App\Transformers\API\OrderPosTransformer;
use App\Http\Requests\StoreOrderPosApiRequest;

class OrderPosApiController extends Controller
{

    public function index(OrderPosApiRequest $request)
    {
        $data = Order::where('is_pos', true)
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where('code', 'ilike', "%{$request->search}%");
        })   
        ->when($request->has('date'), function ($query) use ($request) {
            $query->whereDate('created_at', $request->date);
        })   
        ->whereHas('paymentable', function ($query){
            $query->where('status', PaymentState::PAID);
        })
        ->orderByDesc('id')
        ->get();

        $order = fractal($data, new OrderPosTransformer)->toArray();

        return $this->apiSuccess($order);
    }

    public function store(StoreOrderPosApiRequest $request)
    {
        $validate = $this->validateOrder($request);

        if($validate) return $this->apiError([], [], $validate);
        
        //store order
        $order = (new StoreOrderPosApiAction($request->all()))->handle();
        
        $order = fractal($order, new OrderPosTransformer);           
            
        return $this->apiSuccess($order);
    }

    public function validateOrder($request)
    {        

        $details = collect($request['details']);

        $totalDetail = $details->sum('total');        

        $fixTotal = $totalDetail;

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

        
        if($fixTotal != $request->jumlah){
            return 'Total Invalid';
        }

        
        return false;
            
    }
}
