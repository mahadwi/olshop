<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\AuthenticationDetail;
use Illuminate\Http\Request;
use App\Actions\StoreAuthenticationDetailAction;
use App\Actions\UpdateAuthenticationDetailAction;
use App\Http\Requests\AuthenticationDetailStoreRequest;
use App\Http\Requests\AuthenticationDetailUpdateRequest;

class AuthenticationDetailController extends Controller
{
    public function loadDetailAuthentication(Request $request)
    {
        $authenticationDetail = AuthenticationDetail::get();
        return json_encode($authenticationDetail);
    }

    public function store(AuthenticationDetailStoreRequest $request)
    {
        try {
            $authenticationDetail = dispatch_sync(new StoreAuthenticationDetailAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $authenticationDetail ->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.authentication')]) . $th->getMessage());
        }
    }

    public function update(AuthenticationDetailUpdateRequest $request, AuthenticationDetail $authenticationDetail)
    {
        try {

            $authenticationDetail = dispatch_sync(new UpdateAuthenticationDetailAction($authenticationDetail, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $authenticationDetail->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $authenticationDetail->title]) . $th->getMessage());
        }
    }

    public function destroy($id)
    {
        $authenticationDetail = AuthenticationDetail::find($id);
        if(!$authenticationDetail){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            $authenticationDetail->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $authenticationDetail->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $authenticationDetail->title]) . $th->getMessage());
        }
    }

}
