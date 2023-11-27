<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Actions\StoreImageAction;
use App\Constants\GallerySection;
use App\Actions\StoreGalleryAction;
use App\Services\File\UploadService;
use App\Http\Requests\GalleryStoreRequest;
use App\Http\Requests\GalleryUpdateRequest;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::with('product');
        if ($request->has('search')) {
            $galleries->where('section', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $galleries->orderBy($request->field, $request->order);
        }

        $galleries->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $galleries = $galleries->paginate($perPage);
        
        collect($galleries->items())->map(function($dataGallery){
            $dataGallery->image = $dataGallery->imageName()->map(function ($image){
                return [
                    'source' => $image,
                    'options' => [
                        'type'  => 'local'
                    ]
                ];
            });

            return $dataGallery;
        });

        $gallerySection = GallerySection::getValues();
        $product = Product::where('display_on_homepage', true)
        ->where('history', '!=', null)
        ->get();

        return Inertia::render('Gallery/Index', [
            'title'         => 'Data '.__('app.label.gallery'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'galleries'       => $galleries,
            'gallerySection' => $gallerySection,
            'product' => $product,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.gallery'), 'href' => route('gallery.index')],
            ],
        ]);
    } 

    public function store(GalleryStoreRequest $request)
    {
        try {
            $gallery = dispatch_sync(new StoreGalleryAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $gallery->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.gallery')]) . $th->getMessage());
        }
    }

    public function update(GalleryUpdateRequest $request, Gallery $gallery)
    {
        try {

            $gallery->fill($request->all());
            $gallery->save();

            return back()->with('success', __('app.label.updated_successfully', ['name' => $gallery->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $gallery->name]) . $th->getMessage());
        }
    }

    public function uploadImage(Request $request, Gallery $gallery)
    {
        
        try {

            $file = (new UploadService())->saveFile($request->image); 
            
            $image = dispatch_sync(new StoreImageAction(['name' => $file['name']], $gallery));

            return response()->json($image->name);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function destroy(Gallery $gallery)
    {
        try {

            if($gallery->imageable->isNotEmpty()){
                (new UploadService())->deleteFile($gallery->imageable);
                $gallery->imageable()->delete();
            }
            
            $gallery->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $gallery->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $gallery->name]) . $th->getMessage());
        }
    }
}
