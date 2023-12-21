<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Authentication;
use App\Models\AuthenticationDetail;
use Illuminate\Http\Request;
use App\Actions\StoreAuthenticationAction;
use App\Actions\UpdateAuthenticationAction;
use App\Http\Requests\AuthenticationStoreRequest;
use App\Http\Requests\AuthenticationUpdateRequest;

class AuthenticationController extends Controller
{
    public function index(Request $request)
    {
        $authentication = Authentication::query();
        $authenticationDetail = AuthenticationDetail::query();

        if ($request->has('search')) {
            $authenticationDetail->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $authenticationDetail->orderBy($request->field, $request->order);
        }

        $authenticationDetail->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
        return Inertia::render('Authentication/Index', [
            'title' => 'Data '.__('app.label.authentication'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'authentications'     => $authentication->paginate($perPage),
            'detailAuthentications' => $authenticationDetail->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.authentication'), 'href' => route('authentication.index')],
            ],
        ]);
    }

    public function store(AuthenticationStoreRequest $request)
    {
        try {
            $returnPolice = dispatch_sync(new StoreAuthenticationAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $returnPolice ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.return_police')]) . $th->getMessage());
        }
    }

    public function update(AuthenticationUpdateRequest $request, Authentication $authentication)
    {
        try {

            $authentication = dispatch_sync(new UpdateAuthenticationAction($authentication, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $authentication->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $authentication->title]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $authentication = Authentication::find($id);
        if(!$authentication){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $authentication->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $authentication->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $authentication->name]) . $th->getMessage());
        }
    }
}
