<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Actions\StoreBrandAction;
use App\Actions\UpdateBrandAction;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::query();
        if ($request->has('search')) {
            $brands->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $brands->orderBy($request->field, $request->order);
        }

        $brands->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Brand/Index', [
            'title'         => 'Data '.__('app.label.brand'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'brands'         => $brands->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.brand'), 'href' => route('brand.index')],
            ],
        ]);
    }    

    public function store(BrandStoreRequest $request)
    {
        try {
            $brand = dispatch_sync(new StoreBrandAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $brand->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.brand')]) . $th->getMessage());
        }
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        try {

            $brand = dispatch_sync(new UpdateBrandAction($brand, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $brand->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $brand->name]) . $th->getMessage());
        }
    }


    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $brand->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $brand->name]) . $th->getMessage());
        }
    }
}
