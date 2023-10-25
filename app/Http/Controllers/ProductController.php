<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Brand;
use App\Constants\Role;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Actions\StoreProductAction;
use App\Actions\UpdateProductAction;
use App\Constants\CommissionType;
use App\Constants\VendorType;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->vendors = User::whereNotNull('vendor_type')
        ->where('vendor_type', '!=', VendorType::ASET)
        ->active()->get();

        $this->commissionType = CommissionType::getValues();
    }

    public function index(ProductIndexRequest $request)
    {
        $products = Product::query();
        
        if ($request->has('search')) {

            $products->where('name', 'ILIKE', "%" . $request->search . "%")
            ->orWhereHas('brand', function($q) use ($request){
                $q->where('name', 'ILIKE', "%" . $request->search . "%");
            });
            // $products->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $products->orderBy($request->field, $request->order);
        }

        $products->with(['brand', 'productCategory', 'user'])->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render('Product/Index', [
            'title'         => 'Data '.__('app.label.product'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'products'      => $products->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.product'), 'href' => route('product.index')],
            ],
        ]);
    }

    public function create()
    {   

        $brands = Brand::get();
        $categories = ProductCategory::active()->get();
        
        return Inertia::render('Product/Create', [
            'title'         => 'Create '.__('app.label.product'),
            'categories'    => $categories,
            'vendors'       => $this->vendors,
            'brands'       => $brands,
            'commissionType'    => $this->commissionType,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.product'), 'href' => route('product.index')],
            ],
        ]);
    }

    public function edit(Product $product)
    {   

        $brands = Brand::get();
        $categories = ProductCategory::active()->get();
        
        return Inertia::render('Product/Edit', [
            'title'             => 'Edit '.__('app.label.product'),
            'categories'        => $categories,
            'vendors'           => $this->vendors,
            'product'           => $product,
            'brands'            => $brands,
            'commissionType'    => $this->commissionType,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.product'), 'href' => route('product.index')],
            ],
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        try {

            $product = dispatch_sync(new StoreProductAction($request->all()));

            
            return redirect()->route('product.index')->with('success', __('app.label.created_successfully', ['name' => $product->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.product')]) . $th->getMessage());
        }
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        try {
            $product = dispatch_sync(new UpdateProductAction($product, $request->all()));

            return redirect()->route('product.index')->with('success', __('app.label.updated_successfully', ['name' => $product->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $product->name]) . $th->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $product->name]) . $th->getMessage());
        }
    }
}
