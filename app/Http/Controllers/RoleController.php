<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\RoleIndexRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;

class RoleController extends Controller
{
    public function index(RoleIndexRequest $request)
    {
        $roles = Role::query();
        if ($request->has('search')) {
            $roles->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $roles->orderBy($request->field, $request->order);
        }
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Role/Index', [
            'title'         => 'Data '.__('app.label.role'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'roles'         => $roles->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.role'), 'href' => route('roles.index')]
            ],
        ]);
    }    

    public function store(RoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);
            DB::commit();
            return back()->with('success', __('app.label.created_successfully', ['name' => $role->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.role')]) . $th->getMessage());
        }
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {

            $role = Role::findOrFail($id);
            $role->update([
                'name'      => $request->name,
            ]);

            DB::commit();
            return back()->with('success', __('app.label.updated_successfully', ['name' => $role->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $role->name]) . $th->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $role->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $role->name]) . $th->getMessage());
        }
    }
}
