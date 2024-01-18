<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\VendorProduct;
use Illuminate\Support\Facades\DB;
use App\Constants\VendorProductStatus;
use App\Actions\UpdateVendorProductAction;

class KonsinyasiController extends Controller
{

    public function __construct()
    {
        $this->root = 'Konsinyasi';
    }

    public function index(Request $request)
    {
        $products = VendorProduct::with(['color', 'brand', 'productCategory', 'vendor']);
        
        if ($request->has('search')) {

            $products->where('name', 'ILIKE', "%" . $request->search . "%")
            ->orWhereHas('vendor', function($q) use ($request){
                $q->where('name', 'ILIKE', "%" . $request->search . "%");
            });
            // $products->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $products->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render($this->root.'/Index', [
            'title'         => 'Data '.__('app.label.consignment'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'products'      => $products->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.consignment'), 'href' => route('konsinyasi.index')],
            ],
        ]);
    }

    public function show(VendorProduct $konsinyasi)
    {       
        $vendorProductStatus = collect(VendorProductStatus::getValues());

        return Inertia::render($this->root.'/Show', [
            'title'                 => 'Show '.__('app.label.consignment'),
            'product'               => $konsinyasi->load(['color', 'brand', 'productCategory', 'vendor', 'imageable']),
            'vendorProductStatus'   => $vendorProductStatus,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.consignment'), 'href' => route('konsinyasi.index')],
            ],
        ]);
    }

    public function update(Request $request, VendorProduct $konsinyasi)
    {       
        try {
            dispatch_sync(new UpdateVendorProductAction($konsinyasi, $request->all()));           
            return redirect()->route('konsinyasi.index')->with('success', __('app.label.updated_successfully', ['name' => $konsinyasi->name]));

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $konsinyasi->name]) . $th->getMessage());
        }
    }
}