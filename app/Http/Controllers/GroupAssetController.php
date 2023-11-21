<?php

namespace App\Http\Controllers;

use App\Constants\MetodePenyusutan;
use App\Http\Requests\GroupAssetStoreRequest;
use App\Http\Requests\GroupAssetUpdateRequest;
use App\Models\Coa;
use Inertia\Inertia;
use App\Models\GroupAsset;
use Illuminate\Http\Request;

class GroupAssetController extends Controller
{
    public function index(Request $request)
    {
        $groupAssets = GroupAsset::query();
        if ($request->has('search')) {
            $groupAssets->where('metode_penyusutan', 'ILIKE', "%" . $request->search . "%")
            ->Orwhere('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $groupAssets->orderBy($request->field, $request->order);
        }

        $groupAssets->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $coa = Coa::where('name', 'ilike', '%asset tetap%')->get();
        $coaAkumulasi   = Coa::where('name', 'ilike', '%akumulasi%')->get();
        $coaDepresiasi  = Coa::where('name', 'ilike', '%depresiasi%')->get();

        $metodePenyusutan = collect(MetodePenyusutan::getValues());

        return Inertia::render('GroupAsset/Index', [
            'title'             => 'Data '.__('app.label.group_asset'),
            'filters'           => $request->all(['search', 'field', 'order']),
            'perPage'           => (int) $perPage,
            'groupAssets'        => $groupAssets->paginate($perPage),
            'metodePenyusutan'  => $metodePenyusutan,
            'coa'               => $coa,
            'coaAkumulasi'  => $coaAkumulasi,
            'coaDepresiasi' => $coaDepresiasi,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.group_asset'), 'href' => route('group-asset.index')]
            ],
        ]);
    }  

    public function store(GroupAssetStoreRequest $request)
    {
        try {

            $groupAsset = new GroupAsset($request->all());

            $groupAsset->save();

            return back()->with('success', __('app.label.created_successfully', ['name' => $groupAsset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.group_coa')]) . $th->getMessage());
        }
    }

    public function update(GroupAssetUpdateRequest $request, GroupAsset $groupAsset)
    {   
        try {            
            $groupAsset->fill($request->all())->save();            

            return back()->with('success', __('app.label.updated_successfully', ['name' => $groupAsset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $groupAsset->name]) . $th->getMessage());
        }
    }

    public function destroy(GroupAsset $groupAsset)
    {
        try {
            $groupAsset->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $groupAsset->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $groupAsset->name]) . $th->getMessage());
        }
    }
}
