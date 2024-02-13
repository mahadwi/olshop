<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\StoreWishlistApiRequest;
use App\Models\Wishlist;
use App\Transformers\API\WishListTransformer;
use Illuminate\Http\Request;

class WishlistApiController extends Controller
{

    public function index(Request $request)
    {
        $query = Wishlist::where('user_id', auth()->user()->id)
        ->when($request->has('search'), function ($query) use ($request) {
            $query->whereHas('product', function ($query) use ($request){
                $query->where('name', 'ilike', "%{$request->search}%");
            });            
        });
        
        if ($request->has('page')) {
            if ($request->has('itemPerpage')) {
                $query = $query->paginate($request->itemPerpage);
            } else {
                $query = $query->paginate(10);
            }
        } else {
            $query = $query->get();
        }

        if ($request->has('page')) {
            $wishlist = fractal($query->items(), new WishListTransformer($request->all()))->toArray();
        } else {
            $wishlist = fractal($query, new WishListTransformer($request->all()))->toArray();
        }

        $wishlist = collect($wishlist);

        $wishlistF = [];

        foreach ($wishlist as $dataProdct) {
            $wishlistF[] = $dataProdct;
        }

        $meta = [];
        if (isset($request->page)) {
            $meta = [
                'currentPage' => $query->currentPage(),
                'itemsPerPage' => $query->perPage(),
                'nextPage' => $query->currentPage() < $query->lastPage() ? $query->currentPage() + 1 : null,
                'totalItemCount' => $query->count(),
                'totalPage' => $query->lastPage(),
                'lastPage' => $query->lastPage(),
                'totalItem' => $query->total()
            ];
        }
        return $this->apiSuccess($wishlistF, $meta);
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
