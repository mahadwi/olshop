<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;
use App\Models\PenjualanAsset;
use App\Models\PendaftaranAsset;
use App\Actions\StorePenjualanAssetAction;
use App\Http\Requests\PenjualanAssetStoreRequest;
use App\Models\Customer;

class PenjualanAssetController extends Controller
{
    public function __construct()
    {
        $this->root = 'PenjualanAsset';
        $this->pendaftaranAsset = PendaftaranAsset::with(['asset', 'groupAsset'])
        ->doesntHave('penjualanAsset')
        ->get();
    }

    public function index(Request $request)
    {

        $penjualanAssets = PenjualanAsset::with('pendaftaranAsset');

        if ($request->has('search')) {

            $penjualanAssets->where('nomor', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $penjualanAssets->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $penjualanAssets->orderBy($request->field, $request->order);
        }

        $penjualanAssets->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render($this->root.'/Index', [
            'title'                 => 'Data '.__('app.label.penjualan_asset'),
            'filters'               => $request->all(['search', 'field', 'order']),
            'perPage'               => (int) $perPage,
            'penjualanAssets'       => $penjualanAssets->paginate($perPage),
            'breadcrumbs'           => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.penjualan_asset'), 'href' => route('penjualan-asset.index')],
            ],
        ]);
    }

    public function create()
    {
        $nomor = AppHelper::generateNumber('PenjualanAsset', 'FJA');
        $customer = Customer::all();

        return Inertia::render($this->root.'/Create', [
            'title'          => 'Create '.__('app.label.penjualan_asset'),
            'nomor'          => $nomor,
            'customer'       => $customer,
            'pendaftaranAsset' => $this->pendaftaranAsset,
            'breadcrumbs'    => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.penjualan_asset'), 'href' => route('penjualan-asset.index')],
            ],
        ]);
    }

    public function store(PenjualanAssetStoreRequest $request)
    {
        try {
            $model = dispatch_sync(new StorePenjualanAssetAction($request->all()));

            return redirect()->route('penjualan-asset.index')->with('success', __('app.label.created_successfully', ['name' => $model->nomor]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.penjualan-asset')]) . $th->getMessage());
        }
    }
}
