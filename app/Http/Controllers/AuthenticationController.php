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
use App\Actions\StoreAuthenticationDetailAction;
use App\Actions\UpdateAuthenticationDetailAction;

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
            $authentication = dispatch_sync(new StoreAuthenticationAction($request->all()));

            for ($i = 1; $i <= 3; $i++) {

                $titleSection = "titleSection" . $i;
                $titleEnSection = "titleEnSection" . $i;
                $descriptionSection = "descriptionSection" . $i;
                $descriptionEnSection = "descriptionEnSection" . $i;
                $image = "imageSection" . $i;
                if ($request->$titleSection) {

                    $paramsDetail = [
                        'authentication_id' => $authentication->id,
                        'title' => $request->$titleSection,
                        'title_en' => $request->$titleEnSection,
                        'description' => $request->$descriptionSection,
                        'description_en' => $request->$descriptionEnSection,
                        'image' => $request->$image,
                        'section' => $i
                    ];
                    $authenticationDetail = dispatch_sync(new StoreAuthenticationDetailAction($paramsDetail));
                }
            }

            return back()->with('success', __('app.label.created_successfully', ['name' => $authentication ->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.return_police')]) . $th->getMessage());
        }
    }

    public function update(AuthenticationUpdateRequest $request, Authentication $authentication)
    {
        try {
            $authentication = dispatch_sync(new UpdateAuthenticationAction($authentication, $request->all()));
            for ($i = 1; $i <= 3; $i++) {

                $authenticationDetailId_section = "authenticationDetailId_section" . $i;
                $titleSection = "titleSection" . $i;
                $titleEnSection = "titleEnSection" . $i;
                $descriptionSection = "descriptionSection" . $i;
                $descriptionEnSection = "descriptionEnSection" . $i;
                $image = "imageSection" . $i;
                if ($request->$titleSection) {
                    $paramsDetail = [
                        'authentication_id' => $authentication->id,
                        'title' => $request->$titleSection,
                        'title_en' => $request->$titleEnSection,
                        'description' => $request->$descriptionSection,
                        'description_en' => $request->$descriptionEnSection,
                        'image' => $request->$image,
                        'section' => $i
                    ];

                    if ($request->$authenticationDetailId_section) {
                        $authenticationDetail = AuthenticationDetail::find($request->$authenticationDetailId_section);

                        $dataDetail = dispatch_sync(new UpdateAuthenticationDetailAction($authenticationDetail, $paramsDetail));
                    } else {
                        $authenticationDetail = dispatch_sync(new StoreAuthenticationDetailAction($paramsDetail));
                    }
                }
            }
            return back()->with('success', __('app.label.updated_successfully', ['name' => $authentication->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $authentication->title]) . $th->getMessage());
        }
    }

    public function getDetail(Request $request)
    {
        $data = AuthenticationDetail::where('authentication_id', $request->authentication_id)->get();
        return response()->json($data);
    }

    public function destroy($id)
    {
        $authentication = Authentication::find($id);
        $authenticationDetailWhere = AuthenticationDetail::where('authentication_id', $id)->get();
        if(!$authentication){
            return back()->with('error', __('app.label.deleted_error', ['name' => __('app.label.data_not_found')]));
        }
        try {
            foreach ($authenticationDetailWhere as $k => $v) {
                $authenticationDetail = AuthenticationDetail::find($v->id);
                $authenticationDetail->delete();
            }
            $authentication->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $authentication->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $authentication->name]) . $th->getMessage());
        }
    }
}
