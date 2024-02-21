<?php

namespace App\Http\Controllers\API;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\API\CartTransformer;
use App\Http\Requests\API\StoreCartApiRequest;
use App\Http\Requests\API\UpdateCartApiRequest;
use App\Http\Requests\API\MulitDeleteCartApiRequest;
use App\Models\Product;

class CartApiController extends Controller
{
    public function index()
    {
        $data = Cart::where('user_id', auth()->user()->id)->orderBy('id')->get();

        $cart = fractal($data, new CartTransformer)->toArray();

        return $this->apiSuccess($cart);
    }

    public function store(StoreCartApiRequest $request)
    {
        $cart = Cart::where([
            ['product_id', $request->product_id],
            ['user_id', $request->user_id]
        ])->first();
               
        $outStock = $this->cekStok($request->product_id, $request->qty);
        if($outStock) return $this->apiError([], [], 'Out Of Stock');

        if(!$cart){
            $cart = new Cart($request->all());            
        } else {

            $outStock = $this->cekStok($request->product_id, $cart->qty + 1);
            if($outStock) return $this->apiError([], [], 'Out Of Stock');

            $cart->qty += 1;
        }

        $cart->save();

        $cart = fractal($cart, new CartTransformer);

        return $this->apiSuccess($cart);
    }

    public function update(UpdateCartApiRequest $request, $cart)
    {
        $dataCart = Cart::find($cart);

        if(!$dataCart){
            return $this->apiError([],[],'Cart not found');
        }        

        $outStock = $this->cekStok($dataCart->product_id, $request->qty);
        if($outStock) return $this->apiError([], [], 'Out Of Stock');

        $dataCart->qty = $request->qty;              
        $dataCart->save();

        $cart = fractal($dataCart, new CartTransformer);

        return $this->apiSuccess($cart);
    }


    public function destroy($cart)
    {        
        $delete = Cart::find($cart);

        if(!$delete){
            return $this->apiError([],[],'Cart not found');
        }

        $delete->delete();
        

        return $this->apiSuccess();
    }

    public function delete(MulitDeleteCartApiRequest $request)
    {
        Cart::destroy($request->cart_id);

        return $this->apiSuccess();
    }

    public function cekStok($productId, $qty)
    {
        $product = Product::find($productId);
        
        return $qty > $product->stock;
    }

}
