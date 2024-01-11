<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\PembelianAsset;
use App\Models\PendaftaranAsset;
use App\Actions\StorePendaftaranAssetAction;
use App\Http\Requests\PendaftaranAssetStoreRequest;

class PendaftaranAssetController extends Controller
{

    public function __construct()
    {
        $this->root = 'PendaftaranAsset';
        $this->pembelianAsset = PembelianAsset::with('asset.groupAsset')
        ->doesntHave('pendaftaranAsset')
        ->get();
    }

    public function index(Request $request)
    {

        $pendaftaranAssets = PendaftaranAsset::with(['pembelianAsset', 'penjualanAsset']);

        if ($request->has('search')) {

            $pendaftaranAssets->where('nomor', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $pendaftaranAssets->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $pendaftaranAssets->orderBy($request->field, $request->order);
        }

        $pendaftaranAssets->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render($this->root.'/Index', [
            'title'                 => 'Data '.__('app.label.pendaftaran_asset'),
            'filters'               => $request->all(['search', 'field', 'order']),
            'perPage'               => (int) $perPage,
            'pendaftaranAssets'       => $pendaftaranAssets->paginate($perPage),
            'breadcrumbs'           => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pendaftaran_asset'), 'href' => route('pendaftaran-asset.index')],
            ],
        ]);
    }

    public function create()
    {
        $nomor = AppHelper::generateNumber('PendaftaranAsset', 'RA');

        return Inertia::render($this->root.'/Create', [
            'title'          => 'Create '.__('app.label.pendaftaran_asset'),
            'nomor'          => $nomor,
            'pembelianAsset' => $this->pembelianAsset,
            'breadcrumbs'    => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pendaftaran_asset'), 'href' => route('pendaftaran-asset.index')],
            ],
        ]);
    }

    public function store(PendaftaranAssetStoreRequest $request)
    {
        try {

            $model = dispatch_sync(new StorePendaftaranAssetAction($request->all()));

            return redirect()->route('pendaftaran-asset.index')->with('success', __('app.label.created_successfully', ['name' => $model->nomor]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.pendaftaran-asset')]) . $th->getMessage());
        }
    }

    public function show(PendaftaranAsset $pendaftaran_asset)
    {
        return Inertia::render($this->root.'/Show', [
            'title'             => 'Show '.__('app.label.pendaftaran_asset'),
            'pendaftaranAsset'    => $pendaftaran_asset->load(['asset', 'groupAsset', 'pembelianAsset', 'penyusutanAsset', 'penjualanAsset']),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.pendaftaran_asset'), 'href' => route('pendaftaran-asset.index')],
            ],
        ]);
    }

    public function destroy(PendaftaranAsset $pendaftaran_asset)
    {
        try {
            $pendaftaran_asset->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $pendaftaran_asset->nomor]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $pendaftaran_asset->nomor]) . $th->getMessage());
        }
    }
}
