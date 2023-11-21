<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Asset;
use App\Models\GroupAsset;
use Illuminate\Http\Request;
use App\Http\Requests\AssetStoreRequest;
use App\Http\Requests\AssetUpdateRequest;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $assets = Asset::with('groupAsset');
        if ($request->has('search')) {
            $assets->where('code', 'ILIKE', "%" . $request->search . "%")
            ->Orwhere('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $assets->orderBy($request->field, $request->order);
        }

        $assets->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $groupAsset = GroupAsset::get();

        return Inertia::render('Asset/Index', [
            'title'             => 'Data '.__('app.label.asset'),
            'filters'           => $request->all(['search', 'field', 'order']),
            'perPage'           => (int) $perPage,
            'assets'        => $assets->paginate($perPage),
            'groupAsset'        => $groupAsset,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.asset'), 'href' => route('asset.index')]
            ],
        ]);
    }  

    public function store(AssetStoreRequest $request)
    {
        try {

            $asset = new Asset($request->all());

            $asset->save();

            return back()->with('success', __('app.label.created_successfully', ['name' => $asset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.group_coa')]) . $th->getMessage());
        }
    }

    public function update(AssetUpdateRequest $request,Asset $asset)
    {   
        try {            
            $asset->fill($request->all())->save();            

            return back()->with('success', __('app.label.updated_successfully', ['name' => $asset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $asset->name]) . $th->getMessage());
        }
    }

    public function destroy(Asset $asset)
    {
        try {
            $asset->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $asset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $asset->name]) . $th->getMessage());
        }
    }
}
