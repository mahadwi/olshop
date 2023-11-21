<?php

namespace App\Http\Controllers;

use App\Actions\UpdateUserAction;
use App\Models\User;
use Inertia\Inertia;
use App\Constants\Role;
use App\Constants\VendorType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VendorIndexRequest;
use App\Http\Requests\VendorUpdateRequest;

class VendorController extends Controller
{
    public function index(VendorIndexRequest $request)
    {
        $vendors = User::query();
        if ($request->has('search')) {
            $vendors->where('name', 'ILIKE', "%" . $request->search . "%");
            $vendors->orWhere('email', 'ILIKE', "%" . $request->search . "%");
            $vendors->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $vendors->orderBy($request->field, $request->order);
        }

        $vendors->role(Role::VENDOR);
        $type = VendorType::getValues();

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        
        return Inertia::render('Vendor/Index', [
            'title'         => 'Data Vendor',
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'vendors'         => $vendors->paginate($perPage),
            'type'          => $type,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.vendor'), 'href' => route('vendor.index')]
            ],
        ]);
    }

    public function update(VendorUpdateRequest $request, $id)
    {
        try {

            $user = User::findOrFail($id);
            dispatch_sync(new UpdateUserAction($user, $request->all()));           
            return back()->with('success', __('app.label.updated_successfully', ['name' => $user->name]));

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $user->name]) . $th->getMessage());
        }
    }

    public function destroy(User $vendor)
    {
        try {
            $vendor->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $vendor->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $vendor->name]) . $th->getMessage());
        }
    }
}
