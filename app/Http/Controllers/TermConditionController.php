<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\TermCondition;
use Illuminate\Http\Request;
use App\Actions\StoreTermConditionAction;
use App\Actions\UpdateTermConditionAction;
use App\Http\Requests\TermConditionStoreRequest;
use App\Http\Requests\TermConditionUpdateRequest;

class TermConditionController extends Controller
{
    public function index(Request $request)
    {
        $termCondition = TermCondition::query();
        if ($request->has('search')) {
            $termCondition->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $termCondition->orderBy($request->field, $request->order);
        }

        $termCondition->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('TermCondition/Index', [
            'title' => 'Data '.__('app.label.term_condition'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'termConditions'         => $termCondition->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.term_condition'), 'href' => route('term-condition.index')],
            ],
        ]);
    }

    public function store(TermConditionStoreRequest $request)
    {
        try {
            $termCondition = dispatch_sync(new StoreTermConditionAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $termCondition ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.term_condition')]) . $th->getMessage());
        }
    }

    public function update(TermConditionUpdateRequest $request, TermCondition $termCondition)
    {
        try {
            $termCondition = dispatch_sync(new UpdateTermConditionAction($termCondition, $request->all()));
            return back()->with('success', __('app.label.updated_successfully', ['name' => $termCondition->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $termCondition->name]) . $th->getMessage());
        }
    }

    public function destroy(TermCondition $termCondition)
    {
        try {
            $termCondition->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $termCondition->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $termCondition->name]) . $th->getMessage());
        }
    }
}
