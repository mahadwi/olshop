<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\BankCode;
use App\Models\Rekening;
use Illuminate\Http\Request;
use App\Actions\StoreRekeningAction;
use App\Actions\UpdateRekeningAction;
use App\Http\Requests\RekeningStoreRequest;
use App\Http\Requests\RekeningUpdateRequest;

class RekeningController extends Controller
{
    public function index(Request $request)
    {
        
        $bankCode = BankCode::all();

        $rekenings = Rekening::query();

        if ($request->has('search')) {
            $rekenings->where('bank', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $rekenings->orderBy($request->field, $request->order);
        }

        $rekenings->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('Rekening/Index', [
            'title'         => 'Data '.__('app.label.rekening'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'bankCode'      => $bankCode,
            'rekenings'         => $rekenings->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.rekening'), 'href' => route('rekening.index')],
            ],
        ]);
    }    

    public function store(RekeningStoreRequest $request)
    {
        try {
            $rekening = dispatch_sync(new StoreRekeningAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $rekening->bank]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.rekening')]) . $th->getMessage());
        }
    }

    public function update(RekeningUpdateRequest $request, Rekening $rekening)
    {
        try {
            $rekening = dispatch_sync(new UpdateRekeningAction($rekening, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $rekening->bank]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $rekening->bank]) . $th->getMessage());
        }
    }


    public function destroy(Rekening $rekening)
    {
        try {
            $rekening->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $rekening->bank]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $rekening->name]) . $th->getMessage());
        }
    }
}
