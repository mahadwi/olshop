<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Actions\StoreCommissionAction;
use App\Actions\UpdateCommissionAction;
use App\Http\Requests\CommissionStoreRequest;

class CommissionController extends Controller
{
    public function __construct()
    {
        $this->brands = Brand::get();
        $this->categories = ProductCategory::active()->get();
    }

    public function index(Request $request)
    {
        $commissions = Commission::with('brand');
        if ($request->has('search')) {
            $commissions->where('brand_id', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $commissions->orderBy($request->field, $request->order);
        }

        $commissions->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Commission/Index', [
            'title'         => 'Data '.__('app.label.commission'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'commissions'         => $commissions->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.commission'), 'href' => route('commission.index')]
            ],
        ]);
    }   

    public function create()
    {               
        return Inertia::render('Commission/Create', [
            'title'         => 'Create '.__('app.label.commission'),
            'categories'    => $this->categories,
            'brands'       => $this->brands,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.commission'), 'href' => route('commission.index')],
            ],
        ]);
    }

    public function store(CommissionStoreRequest $request)
    {
        try {
            dispatch_sync(new StoreCommissionAction($request->all()));

            return redirect()->route('commission.index')->with('success', 'Success');

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.operational')]) . $th->getMessage());
        }
    }

    public function edit(Commission $commission)
    {
        return Inertia::render('Commission/Edit', [
            'title'             => 'Edit '.__('app.label.commission'),
            'categories'    => $this->categories,
            'brands'        => $this->brands,
            'commission'    => $commission->load('details'),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.commission'), 'href' => route('commission.index')],
            ],
        ]);
    }

    public function update(CommissionStoreRequest $request, Commission $commission, UpdateCommissionAction $action)
    {
        try {
            $action->handle($commission, $request->validated());

            return redirect()->route('commission.index')->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Commission $commission)
    {
        try {

            $commission->details()->delete();
            $commission->delete();

            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
