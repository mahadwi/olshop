<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Constants\Ppn;
use App\Models\Vendor;
use App\Models\GroupCoa;
use App\Helpers\AppHelper;
use App\Models\GroupAsset;
use App\Constants\JenisPpn;
use Illuminate\Http\Request;
use App\Constants\VendorType;
use App\Models\PembelianAsset;
use App\Actions\StorePembelianAssetAction;
use App\Actions\UpdatePembelianAssetAction;
use App\Http\Requests\PembelianAssetStoreRequest;
use App\Http\Requests\PembelianAssetUpdateRequest;

class PembelianAssetController extends Controller
{
    
    public function __construct()
    {
        $this->ppn = Ppn::PPN;
        $this->groupAsset = GroupAsset::with('assets')->get();
        $this->vendor = Vendor::where('type', VendorType::ASET)->get();
        $this->root = 'PembelianAsset';
        $this->jenisPpn = JenisPpn::getValues();
    }

    public function index(Request $request)
    {

        $pembelianAssets = PembelianAsset::with('asset');

        if ($request->has('search')) {

            $pembelianAssets->where('nomor', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $pembelianAssets->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $pembelianAssets->orderBy($request->field, $request->order);
        }

        $pembelianAssets->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render($this->root.'/Index', [
            'title'                 => 'Data '.__('app.label.pembelian_asset'),
            'filters'               => $request->all(['search', 'field', 'order']),
            'perPage'               => (int) $perPage,
            'pembelianAssets'       => $pembelianAssets->paginate($perPage),
            'breadcrumbs'           => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pembelian_asset'), 'href' => route('pembelian-asset.index')],
            ],
        ]);
    }

    public function create()
    {

        $nomor = AppHelper::generateNumber('PembelianAsset', 'FA');

        return Inertia::render($this->root.'/Create', [
            'title'         => 'Create '.__('app.label.pembelian_asset'),
            'nomor'         => $nomor,
            'groupAsset'    => $this->groupAsset,
            'ppn'           => $this->ppn,
            'vendor'        => $this->vendor,
            'jenisPpn'      => $this->jenisPpn,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pembelian_asset'), 'href' => route('pembelian-asset.index')],
            ],
        ]);
    }

    public function store(PembelianAssetStoreRequest $request)
    {
        try {

            $model = dispatch_sync(new StorePembelianAssetAction($request->all()));

            return redirect()->route('pembelian-asset.index')->with('success', __('app.label.created_successfully', ['name' => $model->nomor]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.pembelian-asset')]) . $th->getMessage());
        }
    }

    public function edit(PembelianAsset $pembelian_asset)
    {
        
        return Inertia::render($this->root.'/Edit', [
            'title'             => 'Edit '.__('app.label.pembelian_asset'),
            'pembelianAsset'    => $pembelian_asset->load('asset.groupAsset'),
            'groupAsset'    => $this->groupAsset,
            'ppn'           => $this->ppn,
            'vendor'        => $this->vendor,
            'jenisPpn'      => $this->jenisPpn,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pembelian_asset'), 'href' => route('pembelian-asset.index')],
            ],
        ]);
    }

    public function update(PembelianAssetUpdateRequest $request, PembelianAsset $pembelian_asset)
    {
        try {
            $pembelian_asset = dispatch_sync(new UpdatePembelianAssetAction($pembelian_asset, $request->all()));
            return redirect()->route('pembelian-asset.index')->with('success', __('app.label.updated_successfully', ['name' => $pembelian_asset->nomor]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $pembelian_asset->nomor]) . $th->getMessage());
        }
    }

    public function destroy(PembelianAsset $pembelian_asset)
    {
        try {
            $pembelian_asset->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $pembelian_asset->nomor]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $pembelian_asset->nomor]) . $th->getMessage());
        }
    }

}
