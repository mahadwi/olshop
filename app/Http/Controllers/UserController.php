<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserIndexRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(UserIndexRequest $request)
    {
        $users = User::query();
        if ($request->has('search')) {
            $users->where('name', 'LIKE', "%" . $request->search . "%");
            $users->orWhere('email', 'LIKE', "%" . $request->search . "%");
            $users->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $users->orderBy($request->field, $request->order);
        }
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        $role = auth()->user()->roles->pluck('name')[0];
        $roles = Role::get();
        
        return Inertia::render('User/Index', [
            'title'         => 'Data User',
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'users'         => $users->with('roles')->paginate($perPage),
            'roles'         => $roles,
            // 'breadcrumbs'   => [['label' => __('app.label.user'), 'href' => route('user.index')]],
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_hp' => $request->no_hp,
            ]);
            $user->assignRole($request->role);
            DB::commit();
            return back()->with('success', 'Berhasil');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Error' . $th->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => $request->password ? Hash::make($request->password) : $user->password,
                'no_hp'     => $request->no_hp
            ]);

            $user->syncRoles($request->role);
            DB::commit();
            return back()->with('success', 'Berhasil');
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', 'Gagal'. $th->getMessage());
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return back()->with('success', 'Berhasil');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal'. $th->getMessage());
        }
    }
}
