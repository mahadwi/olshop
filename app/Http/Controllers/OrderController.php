<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Models\Contact;
use App\Services\Ongkir\OngkirService;

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


    public function update(Request $request, Order $order)
    {
        if($request->has('resi')){

            $cekResi = (new OngkirService)->cekResi($order->courier, $request->resi);

            if($cekResi['status'] != 200){
                return back()->with('error', $cekResi['message']);
            }
        }
        $order->fill($request->all())->save();  
        
        return back()->with('success', 'Success');
    }

    public function cekResi(Request $request)
    {
       $cekResi = (new OngkirService)->cekResi($request->courier, $request->resi);

       return $cekResi;
    }

    public function printLabel(Order $order)
    {
        $profile = Profile::first();
        $contact = Contact::first();


        // dd($order->orderDetail);
        $profile->telp = $contact->telp;
        return Inertia::render('Order/Label', [
            'title'         => 'Label_' . $order->code,
            'profile'       => $profile,
            'order'         => $order->load(['orderDetail.product.images', 'paymentable', 'user', 'address.subdistrict']),
        ]);
    }
}
