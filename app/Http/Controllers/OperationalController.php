<?php

namespace App\Http\Controllers;

use App\Actions\StoreOperationAction;
use App\Http\Requests\OperationalStoreRequest;
use App\Models\Operational;
use App\Models\PickupDuration;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OperationalController extends Controller
{
    public function index(Request $request)
    {
        $duration = PickupDuration::first();

        $operationals = Operational::orderBy('id')->get();
        
        return Inertia::render('Operational/Index', [
            'title'         => 'Data '.__('app.label.operational'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'duration'      => $duration,
            'operationals'  => $operationals,
            'breadcrumbs'   => [
                ['label' => 'Data Setting', 'href' => '#'],
                ['label' => __('app.label.operational'), 'href' => route('operational.index')],
            ],
        ]);
    } 

    public function store(OperationalStoreRequest $request)
    {
        try {
            $operational = dispatch_sync(new StoreOperationAction($request->all()));

            return back()->with('success', 'Success');

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.operational')]) . $th->getMessage());
        }
    }

}
