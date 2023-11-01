<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Color;
use App\Constants\Role;
use App\Models\Product;
use App\Constants\Condition;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Constants\VendorType;
use Illuminate\Http\Response;
use App\Models\ProductCategory;
use App\Constants\CommissionType;
use App\Actions\StoreProductAction;
use App\Actions\UpdateProductAction;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Transformers\ProductImageTransformer;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->vendors = User::whereNotNull('vendor_type')
        ->where('vendor_type', '!=', VendorType::ASET)
        ->active()->get();

        $this->commissionType = CommissionType::getValues();
        $this->colors = Color::get();
        $this->condition = Condition::getValues();

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
            'colors'       => $this->colors,
            'commissionType'    => $this->commissionType,
            'condition'    => $this->condition,
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
            'colors'            => $this->colors,
            'commissionType'    => $this->commissionType,
            'condition'         => $this->condition,
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

    public function uploadImage(Request $request, Product $product)
    {
        dd($request->all(), $product);
        try {
            $product = dispatch_sync(new UpdateProductAction($product, $request->all()));

            return redirect()->route('product.index')->with('success', __('app.label.updated_successfully', ['name' => $product->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $product->name]) . $th->getMessage());
        }
    }

    public function getImage(productImage $productImage)
    {
        $filePath = public_path('image/product/'.$productImage->name);
        // $filename = $productImage->name;

        // // Buat response
        // $response = file_get_contents($filePath);

        // $mimeType = mime_content_type($filePath);

        //  // Buat objek Blob dari string
        // $blob = new Response($response);
        // $blob->header('Content-Type', $mimeType);
        // $blob->header('Content-Disposition', 'inline; filename="' . $filename . '"');

        // File path to the file you want to display

        // Custom headers
        $headers = [
            'Content-Type' => 'image/png', // Set the appropriate content type for your file type
            'Content-Disposition' => 'inline; filename="my-file.png"',
        ];

        return response()->file($filePath, $headers);
        // return $blob;
    }

    public function deleteImage(Request $request)
    {
        dd($request->all());
        try {
            $product->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $product->name]) . $th->getMessage());
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
