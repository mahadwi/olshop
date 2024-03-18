<?php

namespace App\Http\Controllers\API;

use App\Actions\API\UpdateOrderPickupAction;
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
        $data = Order::where('is_offline', true)             
        ->when($request->has('search'), function ($query) use ($request) {
            $query->whereHas('orderDetail.product', function ($query) use ($request){
                $query->where('name', 'ilike', "%{$request->search}%");
            });            
        })               
        ->orderByDesc('id')
        ->get();

        $order = fractal($data, new OrderTransformer)->toArray();

        return $this->apiSuccess($order);
    }

    public function update(UpdateOrderPickupRequest $request, Order $order_pickup, UpdateOrderPickupAction $action)
    {
        $update = $action->handle($order_pickup, $request->validated());
        
        return $this->apiSuccess();
    }
}
