<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Actions\StoreCustomerAction;
use App\Actions\UpdateCustomerAction;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::with('penjualanAsset');
        if ($request->has('search')) {
            $customers->where('name', 'ILIKE', "%" . $request->search . "%");
            $customers->orWhere('email', 'ILIKE', "%" . $request->search . "%");
            $customers->orWhere('phone', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $customers->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        
        return Inertia::render('Customer/Index', [
            'title'         => 'Data Customer',
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'customers'         => $customers->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.customer'), 'href' => route('customer.index')]
            ],
        ]);
    }

    public function store(CustomerStoreRequest $request)
    {
        try {
            $model = dispatch_sync(new StoreCustomerAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $model->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.customer')]) . $th->getMessage());
        }
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        try {
            dispatch_sync(new UpdateCustomerAction($customer, $request->all()));           
            return back()->with('success', __('app.label.updated_successfully', ['name' => $customer->name]));

        } catch (\Throwable $th) {
            DB::rollback();
            return back()->with('error', __('app.label.updated_error', ['name' => $customer->name]) . $th->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $customer->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $customer->name]) . $th->getMessage());
        }
    }
}
