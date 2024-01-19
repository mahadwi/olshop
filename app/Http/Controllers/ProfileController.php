<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Profile;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use App\Actions\StoreProfileAction;
use App\Actions\UpdateProfileAction;
use App\Http\Requests\ProfileStoreRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Transformers\API\SubdistrictTransformer;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $profiles = Profile::with('subdistrict.city.province');
        if ($request->has('search')) {
            $profiles->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $profiles->orderBy($request->field, $request->order);
        }

        $profiles->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Profile/Index', [
            'title'         => 'Data '.__('app.label.profile'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'profiles'      => $profiles->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.profile'), 'href' => route('profile.index')],
            ],
        ]);
    }    

    public function store(ProfileStoreRequest $request)
    {
        try {
            $profile = dispatch_sync(new StoreProfileAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $profile->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.profile')]) . $th->getMessage());
        }
    }

    public function update(ProfileUpdateRequest $request, Profile $profile)
    {
        try {

            $profile = dispatch_sync(new UpdateProfileAction($profile, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $profile->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $profile->name]) . $th->getMessage());
        }
    }


    public function destroy(Profile $profile)
    {
        try {
            $profile->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $profile->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $profile->name]) . $th->getMessage());
        }
    }

    public function getKecamatan(Request $request)
    {
        $query = $request->get('query');

        $data = Subdistrict::where('name', 'ILIKE', "%" . $query . "%")->get();

        $result = fractal($data, new SubdistrictTransformer())->toArray();

        return response()->json($result);
    }
}
