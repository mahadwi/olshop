<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreWishlistApiRequest;
use App\Models\Wishlist;
use App\Transformers\API\WishListTransformer;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{

    public function index()
    {
        $data = Wishlist::where('user_id', auth()->user()->id)->get();

        $wishlist = fractal($data, new WishListTransformer)->toArray();

        return $this->apiSuccess($wishlist);
    }

    public function store(StoreWishlistApiRequest $request)
    {
        $wishlist = Wishlist::firstOrCreate([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id
        ]);

        $wishlist = fractal($wishlist, new WishListTransformer);

        return $this->apiSuccess($wishlist);
    }


    public function destroy($wishlist)
    {        
        $delete = Wishlist::find($wishlist);
        if(!$delete){
            return $this->apiError([],[],'Wishlist not found');
        }
        $delete->delete();

        return $this->apiSuccess();
    }
}
