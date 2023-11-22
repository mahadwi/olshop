<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\PrivacyPolice;
use Illuminate\Http\Request;
use App\Actions\StorePrivacyPoliceAction;
use App\Actions\UpdatePrivacyPoliceAction;
use App\Http\Requests\PrivacyPoliceStoreRequest;
use App\Http\Requests\PrivacyPoliceUpdateRequest;

class PrivacyPoliceController extends Controller
{
    public function index(Request $request)
    {
        $privacyPolice = PrivacyPolice::query();
        if ($request->has('search')) {
            $privacyPolice->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $privacyPolice->orderBy($request->field, $request->order);
        }

        $privacyPolice->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('PrivacyPolice/Index', [
            'title' => 'Data '.__('app.label.privacy_police'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'privacyPolices'         => $privacyPolice->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.privacy_police'), 'href' => route('privacy-police.index')],
            ],
        ]);
    }

    public function store(PrivacyPoliceStoreRequest $request)
    {
        try {
            $privacyPolice = dispatch_sync(new StorePrivacyPoliceAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $privacyPolice ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.privacy_police')]) . $th->getMessage());
        }
    }

    public function update(PrivacyPoliceUpdateRequest $request, PrivacyPolice $privacyPolice)
    {
        try {
            $privacyPolice = dispatch_sync(new UpdatePrivacyPoliceAction($privacyPolice, $request->all()));
            return back()->with('success', __('app.label.updated_successfully', ['name' => $privacyPolice->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $privacyPolice->name]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $privacyPolice = PrivacyPolice::find($id);
        if(!$privacyPolice){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $privacyPolice->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $privacyPolice->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $privacyPolice->name]) . $th->getMessage());
        }
    }
}
