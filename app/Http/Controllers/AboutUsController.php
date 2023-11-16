<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use App\Actions\StoreAboutUsAction;
use App\Actions\UpdateAboutUsAction;
use App\Http\Requests\AboutUsStoreRequest;
use App\Http\Requests\AboutUsUpdateRequest;

class AboutUsController extends Controller
{
    public function index(Request $request)
    {
        $abouts = AboutUs::query();
        if ($request->has('search')) {
            $abouts->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $abouts->orderBy($request->field, $request->order);
        }

        $abouts->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('AboutUs/Index', [
            'title'         => 'Data '.__('app.label.about_us'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'abouts'         => $abouts->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.about_us'), 'href' => route('about.index')],
            ],
        ]);
    }   

    public function store(AboutUsStoreRequest $request)
    {
        try {
            $aboutUs = dispatch_sync(new StoreAboutUsAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $aboutUs->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.about_us')]) . $th->getMessage());
        }
    }

    public function update(AboutUsUpdateRequest $request, AboutUs $about)
    {
        try {

            $about = dispatch_sync(new UpdateAboutUsAction($about, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $about->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $about->name]) . $th->getMessage());
        }
    }


    public function destroy(AboutUs $about)
    {
        try {
            $about->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $about->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $about->name]) . $th->getMessage());
        }
    }
}
