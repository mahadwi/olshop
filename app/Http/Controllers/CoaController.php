<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Constants\NormalBalance;
use App\Constants\StatusCoa;
use App\Http\Requests\CoaStoreRequest;
use App\Http\Requests\CoaUpdateRequest;
use App\Models\GroupCoa;

class CoaController extends Controller
{
    public function index(Request $request)
    {
        $coas = Coa::query();
        if ($request->has('search')) {
            $coas->where('code', 'ILIKE', "%" . $request->search . "%")
            ->Orwhere('name', 'ILIKE', "%" . $request->search . "%")
            ->Orwhere('normal_balance', 'ILIKE', "%" . $request->search . "%")
            ->Orwhere('status', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $coas->orderBy($request->field, $request->order);
        }

        $coas->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $normalBalance = collect(NormalBalance::getValues());
        $groupCoa = GroupCoa::all();
        $status = StatusCoa::getValues();

        return Inertia::render('Coa/Index', [
            'title'             => 'Data '.__('app.label.coa'),
            'filters'           => $request->all(['search', 'field', 'order']),
            'perPage'           => (int) $perPage,
            'coas'              => $coas->with('groupCoa')->paginate($perPage),
            'normalBalance'     => $normalBalance,
            'groupCoa'          => $groupCoa,
            'status'            => $status,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.coa'), 'href' => route('group-coa.index')]
            ],
        ]);
    }  

    public function store(CoaStoreRequest $request)
    {
        try {
            $coa = new Coa;
            $coa->fill($request->all())->save();
            
            return back()->with('success', __('app.label.created_successfully', ['name' => $coa->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.group_coa')]) . $th->getMessage());
        }
    }

    public function update(CoaUpdateRequest $request, Coa $coa)
    {
        try {            

            $coa->fill($request->all())->save();

            return back()->with('success', __('app.label.updated_successfully', ['name' => $coa->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $coa->name]) . $th->getMessage());
        }
    }

    public function destroy(Coa $coa)
    {
        try {
            $coa->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $coa->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $coa->name]) . $th->getMessage());
        }
    }
}
