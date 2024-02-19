<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Agreement;
use Illuminate\Http\Request;
use App\Actions\StoreAgreementAction;
use App\Actions\UpdateAgreementAction;
use App\Constants\AgreementFileType;
use App\Http\Requests\AgreementStoreRequest;
use App\Http\Requests\AgreementUpdateRequest;

class AgreementController extends Controller
{
    
    public function index(Request $request)
    {
        $fileType = AgreementFileType::getValues();
        $agreements = Agreement::with('vendors');

        if ($request->has('search')) {
            $agreements->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $agreements->orderBy($request->field, $request->order);
        }

        $agreements->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Agreement/Index', [
            'title'         => 'Data '.__('app.label.agreement'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'fileType'      => $fileType,
            'agreements'    => $agreements->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.agreement'), 'href' => route('agreement.index')],
            ],
        ]);
    }    

    public function store(AgreementStoreRequest $request)
    {
        try {
            $profile = dispatch_sync(new StoreAgreementAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $profile->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.profile')]) . $th->getMessage());
        }
    }

    public function update(AgreementUpdateRequest $request, Agreement $agreement)
    {
        try {
            $agreement = dispatch_sync(new UpdateAgreementAction($agreement, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $agreement->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $agreement->name]) . $th->getMessage());
        }
    }


    public function destroy(Agreement $agreement)
    {
        try {
            $agreement->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $agreement->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $agreement->name]) . $th->getMessage());
        }
    }
}
