<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Banner;
use Illuminate\Http\Request;
use App\Constants\BannerSection;
use App\Actions\StoreImageAction;
use App\Actions\StoreBannerAction;
use App\Services\File\UploadService;
use App\Http\Requests\BannerStoreRequest;
use App\Http\Requests\BannerUpdateRequest;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $banners = Banner::query();
        if ($request->has('search')) {
            $banners->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $banners->orderBy($request->field, $request->order);
        }

        $banners->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $banners = $banners->paginate($perPage);
        
        collect($banners->items())->map(function($dataBanner){
            $dataBanner->image = $dataBanner->imageName()->map(function ($image){
                return [
                    'source' => $image,
                    'options' => [
                        'type'  => 'local'
                    ]
                ];
            });

            return $dataBanner;
        });

        $bannerSection = BannerSection::getValues();

        return Inertia::render('Banner/Index', [
            'title'         => 'Data '.__('app.label.banner'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'banners'       => $banners,
            'bannerSection' => $bannerSection,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.banner'), 'href' => route('banner.index')],
            ],
        ]);
    } 

    public function update(BannerUpdateRequest $request, Banner $banner)
    {
        try {

            $banner->fill($request->all());
            $banner->save();

            return back()->with('success', __('app.label.updated_successfully', ['name' => $banner->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $banner->name]) . $th->getMessage());
        }
    }


    public function uploadImage(Request $request, Banner $banner)
    {
        
        try {

            $file = (new UploadService())->saveFile($request->image); 
            
            $image = dispatch_sync(new StoreImageAction(['name' => $file['name']], $banner));

            return response()->json($image->name);

        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
    
    public function store(BannerStoreRequest $request)
    {
        try {
            $banner = dispatch_sync(new StoreBannerAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $banner->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.banner')]) . $th->getMessage());
        }
    }

    public function destroy(Banner $banner)
    {
        try {
            $banner->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $banner->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $banner->name]) . $th->getMessage());
        }
    }

}
