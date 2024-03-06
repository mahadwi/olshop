<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Vendor;
use App\Constants\Role;
use Illuminate\Http\Request;
use App\Constants\VendorType;
use App\Actions\UpdateUserAction;
use App\Actions\StoreVendorAction;
use Illuminate\Support\Facades\DB;
use App\Actions\UpdateVendorAction;
use App\Http\Requests\VendorIndexRequest;
use App\Http\Requests\VendorStoreRequest;
use App\Http\Requests\VendorUpdateRequest;

class VendorController extends Controller
{
    public function index(VendorIndexRequest $request)
    {
        $vendors = Vendor::query();
        if ($request->has('search')) {
            $vendors->where('name', 'ILIKE', "%" . $request->search . "%");
            $vendors->orWhere('email', 'ILIKE', "%" . $request->search . "%");
            $vendors->orWhere('phone', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $vendors->orderBy($request->field, $request->order);
        }

        $type = VendorType::getValues();

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        
        return Inertia::render('Vendor/Index', [
            'title'         => 'Data Consigner',
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'vendors'         => $vendors->paginate($perPage),
            'type'          => $type,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.vendor'), 'href' => route('consignor.index')]
            ],
        ]);
    }

    public function store(VendorStoreRequest $request)
    {
        try {
            $vendor = dispatch_sync(new StoreVendorAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $vendor->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.vendor$vendor')]) . $th->getMessage());
        }
    }

    public function update(VendorUpdateRequest $request, Vendor $consignor)
    {
        try {
            dispatch_sync(new UpdateVendorAction($consignor, $request->all()));           
            return back()->with('success', __('app.label.updated_successfully', ['name' => $consignor->name]));

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $consignor->name]) . $th->getMessage());
        }
    }

    public function destroy(Vendor $consignor)
    {
        try {
            $consignor->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $consignor->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $consignor->name]) . $th->getMessage());
        }
    }
}
