<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrderRequest $request)
    {        
        $orders = Order::with(['orderDetail.product.images', 'paymentable', 'user', 'address'])
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
        ->orderByDesc('id')->get();

        return Inertia::render('Order/Index', [
            'title'         => 'Data '.__('app.label.order'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'orders'  => $orders,
            'breadcrumbs'   => [
                ['label' => 'Transaction', 'href' => '#'],
                ['label' => __('app.label.order'), 'href' => route('order.index')],
            ],
        ]);
    } 
}
