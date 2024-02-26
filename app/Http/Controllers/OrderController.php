<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {

        $orders = Order::with(['orderDetail.product', 'paymentable'])
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
