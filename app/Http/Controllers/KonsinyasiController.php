<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\VendorProduct;
use App\Models\VendorAgreement;
use Illuminate\Support\Facades\DB;
use App\Constants\VendorProductStatus;
use App\Actions\UpdateVendorProductAction;
use App\Actions\UpdateVendorAgreementAction;
use App\Services\VendorProduct\SetCompleteService;

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
        } else {
            $products->orderBy('id');
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
        $vendorProductStatus = collect(VendorProductStatus::getValues())->except('COMPLETED');

        return Inertia::render($this->root.'/Show', [
            'title'                 => 'Show '.__('app.label.consignment'),
            'product'               => $konsinyasi->load(['color', 'brand', 'productCategory', 'vendor', 'imageable', 'agreements.agreement']),
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

    public function updateAgreement(Request $request)
    {
        try {
            foreach($request->agreement as $agreement){
                $model = VendorAgreement::find($agreement['id']);
                $param = [
                    'is_approved' => $agreement['is_approved'],
                    'note' => $agreement['note'],
                ];
                dispatch_sync(new UpdateVendorAgreementAction($model, $param));           
            }
            return redirect()->route('konsinyasi.index')->with('success', 'Success');

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error') . $th->getMessage());
        }
    }

    public function complete(Request $request)
    {
        try {

            $complete = (new SetCompleteService())->handle($request->id);  

            return redirect()->route('konsinyasi.index')->with('success', 'Success');

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error') . $th->getMessage());
        }
    }
}
