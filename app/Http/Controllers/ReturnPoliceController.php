<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\ReturnPolice;
use Illuminate\Http\Request;
use App\Actions\StoreReturnPoliceAction;
use App\Actions\UpdateReturnPoliceAction;
use App\Http\Requests\ReturnPoliceStoreRequest;
use App\Http\Requests\ReturnPoliceUpdateRequest;

class ReturnPoliceController extends Controller
{
    public function index(Request $request)
    {
        $returnPolice = ReturnPolice::query();
        if ($request->has('search')) {
            $returnPolice->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $returnPolice->orderBy($request->field, $request->order);
        }

        $returnPolice->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('ReturnPolice/Index', [
            'title' => 'Data '.__('app.label.return_police'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'returnPolices'         => $returnPolice->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.return_police'), 'href' => route('returnPolice.index')],
            ],
        ]);
    }

    public function store(ReturnPoliceStoreRequest $request)
    {
        try {
            $returnPolice = dispatch_sync(new StoreReturnPoliceAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $returnPolice ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.return_police')]) . $th->getMessage());
        }
    }

    public function update(ReturnPoliceUpdateRequest $request, ReturnPolice $returnPolice)
    {
        try {

            $about = dispatch_sync(new UpdateReturnPoliceAction($returnPolice, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $returnPolice->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $returnPolice->name]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $returnPolice = ReturnPolice::find($id);
        if(!$returnPolice){
            return back()->with('error', __('app.label.deleted_error', ['name' => 'Data Tidak Ditemukan']));
        }
        try {
            $returnPolice->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $returnPolice->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $returnPolice->name]) . $th->getMessage());
        }
    }
}
