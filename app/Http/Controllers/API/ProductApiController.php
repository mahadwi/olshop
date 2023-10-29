<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use App\Transformers\API\ProductTransformer;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request)
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
        // if (
        //     $request->has('price_min')
        //     && $request->has('price_max')
        // ) {
        //     $product = $product->filter(function ($item) use ($request) {
        //         return $item['sale_price'] > $request->price_min
        //             && $item['sale_price'] < $request->price_max;
        //     });
        // }

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
            ];
        }
        return $this->apiSuccess($productF, $meta);
    }
}
