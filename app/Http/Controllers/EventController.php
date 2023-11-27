<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventStoreRequest;

class EventController extends Controller
{
    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $events = Event::query();
        
        if ($request->has('search')) {

            $events->where('name', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $events->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $events->orderBy($request->field, $request->order);
        }

        $events->with(['brand', 'productCategory', 'user'])->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render('Event/Index', [
            'title'         => 'Data '.__('app.label.event'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'events'      => $events->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }

    public function create()
    {   
        return Inertia::render('Event/Create', [
            'title'         => 'Create '.__('app.label.event'),
            // 'condition'    => $this->condition,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }

    public function edit(Product $product)
    {   

        $brands = Brand::get();
        $categories = ProductCategory::active()->get();
        
        $images = $product->images->pluck('name')->map(function ($image){
            return [
                'source' => $image,
                'options' => [
                    'type'  => 'local'
                ]
            ];
        });

        return Inertia::render('Product/Edit', [
            'title'             => 'Edit '.__('app.label.product'),
            'categories'        => $categories,
            'vendors'           => $this->vendors,
            'product'           => $product,
            'brands'            => $brands,
            'colors'            => $this->colors,
            'images'            => $images,
            'commissionType'    => $this->commissionType,
            'condition'         => $this->condition,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.product'), 'href' => route('product.index')],
            ],
        ]);
    }

    public function store(EventStoreRequest $request)
    {
        try {
            dd($request->all());
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
        try {
            $image = dispatch_sync(new StoreProductImageAction($product, $request->all()));
            return response()->json($image->name);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function getImage($productImage)
    {

        $filePath = public_path('image/product/'.$productImage);
        
        $headers = [
            'Content-Type' => 'image/png', // Set the appropriate content type for your file type
            'Content-Disposition' => 'inline; filename="my-file.png"',
        ];

        return response()->file($filePath, $headers);
    }

    public function deleteImage(Request $request, Product $product)
    {
        
        try {

            $filename = str_replace('"', '', $request->name);
            //cek file
            $cekFile = public_path('image/product/'.$filename);
            if(File::exists($cekFile)){                
                //delete file
                File::delete($cekFile);
            }   

            $image = $product->images->where('name', $filename)->first();
            $image->delete();

            return response()->json('success');

        } catch (\Throwable $th) {
            return response()->json('error');
        }
    }

    public function destroy(Product $product)
    {
        try {
            
            if($product->images->isNotEmpty()){
                (new UploadService())->deleteFile($product->images, 'product');
                $product->images()->delete();
            }
            $product->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $product->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $product->name]) . $th->getMessage());
        }
    }
}
