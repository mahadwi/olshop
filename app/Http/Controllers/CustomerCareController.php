<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\CustomerCare;
use Illuminate\Http\Request;
use App\Actions\StoreCustomerCareAction;
use App\Actions\UpdateCustomerCareAction;
use App\Http\Requests\CustomerCareStoreRequest;
use App\Http\Requests\CustomerCareUpdateRequest;

class CustomerCareController extends Controller
{
    public function index(Request $request)
    {
        $customerCare = CustomerCare::query();
        if ($request->has('search')) {
            $customerCare->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $customerCare->orderBy($request->field, $request->order);
        }

        $customerCare->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('CustomerCare/Index', [
            'title' => 'Data '.__('app.label.customer_care'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'customerCares'         => $customerCare->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.customer_care'), 'href' => route('customer-care.index')],
            ],
        ]);
    }

    public function store(CustomerCareStoreRequest $request)
    {
        try {
            $customerCare = dispatch_sync(new StoreCustomerCareAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $customerCare ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.customer_care')]) . $th->getMessage());
        }
    }

    public function update(CustomerCareUpdateRequest $request, CustomerCare $customerCare)
    {
        try {
            $customerCare = dispatch_sync(new UpdateCustomerCareAction($customerCare, $request->all()));
            return back()->with('success', __('app.label.updated_successfully', ['name' => $customerCare->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $customerCare->name]) . $th->getMessage());
        }
    }

    public function destroy(CustomerCare $customerCare)
    {
        try {
            $customerCare->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $customerCare->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $customerCare->name]) . $th->getMessage());
        }
    }
}
