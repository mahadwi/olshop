<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\API\StoreVendorProductAction;
use App\Http\Requests\API\StoreVendorProductRequest;
use App\Models\VendorProduct;
use App\Repositories\VendorProductRepository;
use App\Transformers\API\VendorProductTransformer;

class VendorProductApiController extends Controller
{

    public function index(Request $request)
    {
        $productSource = (new VendorProductRepository)->getProductApi($request->all());

        if (isset($request->page)) {
            $product = fractal($productSource->items(), new VendorProductTransformer($request->all()))->toArray();
        } else {
            $product = fractal($productSource, new VendorProductTransformer($request->all()))->toArray();
        }
        
        $product = collect($product);

        $productF = [];

        foreach ($product as $dataProdct) {
            $productF[] = $dataProdct;
        }

        $meta = [];
        if (isset($request->page)) {
            $meta = [
                'currentPage' => $productSource->currentPage(),
                'itemsPerPage' => $productSource->perPage(),
                'nextPage' => $productSource->currentPage() < $productSource->lastPage() ? $productSource->currentPage() + 1 : null,
                'totalItemCount' => $productSource->count(),
                'totalPage' => $productSource->lastPage(),
                'lastPage' => $productSource->lastPage(),
                'totalItem' => $productSource->total()
            ];
        }
        
        return $this->apiSuccess($productF, $meta);
    }

    public function store(StoreVendorProductRequest $request)
    {
        $product = (new StoreVendorProductAction($request->all()))->handle();
        
        $product = fractal($product, new VendorProductTransformer);           
            
        return $this->apiSuccess($product);
    }
}
