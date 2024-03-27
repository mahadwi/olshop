<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\OrderTransformer;
use App\Http\Requests\API\OrderPickupApiRequest;
use App\Http\Requests\API\UpdateOrderPickupRequest;

class OrderPickupApiController extends Controller
{
    public function index(OrderPickupApiRequest $request)
    {
        $data = Order::where('courier', 'pickup')             
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where('code', 'ilike', "%{$request->search}%")
            ->orWhereHas('orderDetail.product', function ($query) use ($request){
                $query->where('name', 'ilike', "%{$request->search}%");
            });            
        })               
        ->orderByDesc('id')
        ->get();

        $order = fractal($data, new OrderTransformer)->toArray();

        return $this->apiSuccess($order);
    }

    public function update(UpdateOrderPickupRequest $request, Order $order_pickup)
    {
        $order_pickup->fill($request->all())->save();  
        
        return $this->apiSuccess();
    }
}
