<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\DeliveryShipping;
use Illuminate\Http\Request;
use App\Actions\StoreDeliveryShippingAction;
use App\Actions\UpdateDeliveryShippingAction;
use App\Http\Requests\DeliveryShippingStoreRequest;
use App\Http\Requests\DeliveryShippingUpdateRequest;

class DeliveryShippingController extends Controller
{
    public function index(Request $request)
    {
        $deliveryShipping = DeliveryShipping::query();
        if ($request->has('search')) {
            $deliveryShipping->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $deliveryShipping->orderBy($request->field, $request->order);
        }

        $deliveryShipping->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('DeliveryShipping/Index', [
            'title' => 'Data '.__('app.label.delivery_shipping'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'deliveryShippings'         => $deliveryShipping->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.delivery_shipping'), 'href' => route('delivery-shipping.index')],
            ],
        ]);
    }

    public function store(DeliveryShippingStoreRequest $request)
    {
        try {
            $deliveryShipping = dispatch_sync(new StoreDeliveryShippingAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $deliveryShipping ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.term_condition')]) . $th->getMessage());
        }
    }

    public function update(DeliveryShippingUpdateRequest $request, DeliveryShipping $deliveryShipping)
    {
        try {
            $deliveryShipping = dispatch_sync(new UpdateDeliveryShippingAction($deliveryShipping, $request->all()));
            return back()->with('success', __('app.label.updated_successfully', ['name' => $deliveryShipping->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $deliveryShipping->name]) . $th->getMessage());
        }
    }

    public function destroy(DeliveryShipping $deliveryShipping)
    {
        try {
            $deliveryShipping->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $deliveryShipping->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $deliveryShipping->name]) . $th->getMessage());
        }
    }
}
