<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['paymentable']);
        
        if ($request->has('search')) {

            $orders->where('code', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $orders->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $orders->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render('Pos/Index', [
            'title'         => 'Data '.__('app.label.point_of_sales'),
            'filters'       => $request->all(['search', 'field', 'order']),
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
