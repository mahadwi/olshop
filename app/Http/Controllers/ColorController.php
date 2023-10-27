<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ColorStoreRequest;
use App\Http\Requests\ColorUpdateRequest;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $colors = Color::query();
        if ($request->has('search')) {
            $colors->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $colors->orderBy($request->field, $request->order);
        }
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Color/Index', [
            'title'         => 'Data '.__('app.label.color'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'colors'         => $colors->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.color'), 'href' => route('color.index')]
            ],
        ]);
    }    

    public function store(ColorStoreRequest $request)
    {
        try {
            $color = Color::create([
                'name' => $request->name,
            ]);
            return back()->with('success', __('app.label.created_successfully', ['name' => $color->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.color')]) . $th->getMessage());
        }
    }

    public function update(ColorUpdateRequest $request, Color $color)
    {
        try {
            $color->update([
                'name'      => $request->name,
            ]);

            return back()->with('success', __('app.label.updated_successfully', ['name' => $color->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $color->name]) . $th->getMessage());
        }
    }

    public function destroy(Color $color)
    {
        try {
            $color->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $color->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $color->name]) . $th->getMessage());
        }
    }
}
