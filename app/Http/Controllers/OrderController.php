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

        $order = $request->has('order') ? $request->order : 'desc';

        $orders = Order::with(['orderDetail.product.images', 'paymentable', 'user', 'address'])
        ->when($request->has('status'), function ($query) use ($request) {
            $query->where('status', $request->status);       
        })
        ->when($request->has('search'), function ($query) use ($request) {
            $query->whereHas('orderDetail.product', function ($query) use ($request){
                $query->where('name', 'ilike', "%{$request->search}%");
            });            
        })
        ->when($request->has('is_offline'), function ($query) use ($request) {
            $query->where('is_offline', $request->is_offline);       
        })
        ->orderBy('id', $order);

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Order/Index', [
            'title'         => 'Data '.__('app.label.order'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'orders'         => $orders->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Transaction', 'href' => '#'],
                ['label' => __('app.label.order'), 'href' => route('order.index')],
            ],
        ]);
    } 
}
