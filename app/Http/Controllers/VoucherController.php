<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Constants\VoucherType;
use App\Constants\VoucherUseFor;
use App\Constants\VoucherCapacity;
use App\Actions\StoreVoucherAction;
use App\Http\Requests\VoucherStoreRequest;

class VoucherController extends Controller
{

    public function __construct()
    {
        $this->type = VoucherType::getValues();
        $this->useFor = VoucherUseFor::getValues();
        $this->capacity = VoucherCapacity::getValues();
    }
    
    public function index(Request $request)
    {
        $vouchers = Voucher::query();
        if ($request->has('search')) {
            $vouchers->where('code', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $vouchers->orderBy($request->field, $request->order);
        }

        $vouchers->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        // dd($vouchers->get()[0]);
        $vouchers = $vouchers->paginate($perPage);
        
        return Inertia::render('Voucher/Index', [
            'title'         => 'Data '.__('app.label.voucher'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'vouchers'      => $vouchers,
            'type'          => $this->type,
            'useFor'        => $this->useFor,
            'capacity'      => $this->capacity,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.voucher'), 'href' => route('voucher.index')],
            ],
        ]);
    } 

    public function store(VoucherStoreRequest $request)
    {
        try {
            $voucher = dispatch_sync(new StoreVoucherAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $voucher->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.voucher')]) . $th->getMessage());
        }
    }

    public function update(VoucherStoreRequest $request, Voucher $voucher)
    {
        try {
            $voucher->fill($request->all());
            $voucher->save();

            return back()->with('success', __('app.label.updated_successfully', ['name' => $voucher->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $voucher->name]) . $th->getMessage());
        }
    }

    public function destroy(Voucher $voucher)
    {
        try {

            $voucher->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $voucher->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $voucher->name]) . $th->getMessage());
        }
    }
}
