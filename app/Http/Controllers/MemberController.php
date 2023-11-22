<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Constants\Role;
use Illuminate\Http\Request;
use App\Transformers\API\UserTransformer;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = User::with('addresses.subdistrict.city.province');
        if ($request->has('search')) {
            $members->where('name', 'ILIKE', "%" . $request->search . "%");
            $members->orWhere('email', 'ILIKE', "%" . $request->search . "%");
            $members->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $members->orderBy($request->field, $request->order);
        }

        $members->role(Role::CUSTOMER);

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Member/Index', [
            'title'         => 'Data Member',
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'members'         => $members->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.member'), 'href' => route('member.index')]
            ],
        ]);
    }

    public function show(User $member)
    {
        $dataMember = fractal($member, new UserTransformer)->parseIncludes(['addresses'])->toArray();

        return Inertia::render('Member/Show', [
            'title'         => 'Data Member',
            'member'         => $dataMember,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.member'), 'href' => route('member.index')]
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
