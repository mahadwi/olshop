<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Http\Requests\API\ProductApiRequest;
use App\Transformers\API\ProductTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductApiController extends Controller
{
    public function index(ProductApiRequest $request)
    {
        $productSource = (new ProductRepository)->getProductApi($request->all());
        if (isset($request->page)) {
            $product = fractal($productSource->items(), new ProductTransformer($request->all()))->toArray();
        } else {
            $product = fractal($productSource, new ProductTransformer($request->all()))->toArray();
        }
        
        $product = collect($product);

        if ($request->has('sort')) {
            if ($request->sort['ordering'] === 'desc') {
                $product = collect($product)->sortByDesc($request->sort['name']);
            } else {
                $product = collect($product)->SortBy($request->sort['name']);
            }
        }

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

    public function show($product)
    {
        $product = Product::find($product);
        
        if($product){
            
            $product = fractal($product, new ProductTransformer());

            return $this->apiSuccess($product);
        } else {
            
            return $this->apiError([], [], 'Product Not Found');
        }

    }
}
