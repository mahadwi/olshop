<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {

        $tipe = $request->has('tipe') ? $request->tipe : 'online';

        $orders = Order::with(['paymentable'])
        ->where('is_pos', $tipe == 'offline' ? true : false)
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'ILIKE', "%{$request->search}%");
        });        
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render('Pos/Index', [
            'title'         => 'Data '.__('app.label.point_of_sales'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'tipe'          => $tipe,
            'perPage'       => (int) $perPage,
            'orders'      => $orders->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.point_of_sales'), 'href' => route('pos.index')],
            ],
        ]);
    }

    public function show(Order $po)
    {       
        return Inertia::render('Pos/Show', [
            'title'         => 'Show '.__('app.label.point_of_sales'),
            'order'         => $po->load(['orderDetail.product', 'paymentable']),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.point_of_sales'), 'href' => route('pos.index')],
            ],
        ]);
    }
}
