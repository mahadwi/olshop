<?php

namespace App\Http\Controllers;

use App\Constants\NormalBalance;
use App\Http\Requests\GroupCoaStoreRequest;
use Inertia\Inertia;
use App\Models\GroupCoa;
use Illuminate\Http\Request;

class GroupCoaController extends Controller
{
    public function index(Request $request)
    {
        $groupCoas = GroupCoa::query();
        if ($request->has('search')) {
            $groupCoas->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $groupCoas->orderBy($request->field, $request->order);
        }

        $groupCoas->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $normalBalance = NormalBalance::all();

        return Inertia::render('GroupCoa/Index', [
            'title'             => 'Data '.__('app.label.group_coa'),
            'filters'           => $request->all(['search', 'field', 'order']),
            'perPage'           => (int) $perPage,
            'groupCoas'         => $groupCoas->paginate($perPage),
            'normalBalance' => $normalBalance,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.group_coa'), 'href' => route('group-coa.index')]
            ],
        ]);
    }  

    public function store(GroupCoaStoreRequest $request)
    {
        try {
            $groupCoa = GroupCoa::create([
                'name' => $request->name,
            ]);
            return back()->with('success', __('app.label.created_successfully', ['name' => $groupCoa->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.group_coa')]) . $th->getMessage());
        }
    }

    public function update(ProductCategoryUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->update([
                'name'      => $request->name,
                'is_active' => $request->is_active
            ]);

            DB::commit();
            return back()->with('success', __('app.label.updated_successfully', ['name' => $productCategory->name]));
        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $productCategory->name]) . $th->getMessage());
        }
    }

    public function destroy(ProductCategory $productCategory)
    {
        try {
            $productCategory->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $productCategory->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $productCategory->name]) . $th->getMessage());
        }
    }
}
