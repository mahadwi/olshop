<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Constants\FaqSection;
use App\Http\Requests\FaqStoreRequest;

class FaqController extends Controller
{

    public function index(Request $request)
    {
        $faqs = Faq::query();
        if ($request->has('search')) {
            $faqs->where('section', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $faqs->orderBy($request->field, $request->order);
        }

        $faqs->orderBy('id');
        
        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $section = FaqSection::getValues();

        $faqs = $faqs->paginate($perPage);
        
        return Inertia::render('Faq/Index', [
            'title'         => 'Data '.__('app.label.faq'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'faqs'       => $faqs,
            'section' => $section,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.faq'), 'href' => route('faq.index')],
            ],
        ]);
    } 

    public function store(FaqStoreRequest $request)
    {
        try {
            $faq = new Faq($request->all());
            $faq->save();

            return back()->with('success', __('app.label.created_successfully', ['name' => $faq->title]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.faq')]) . $th->getMessage());
        }
    }

    public function update(FaqStoreRequest $request, Faq $faq)
    {
        try {
            $faq->fill($request->all());
            $faq->save();

            return back()->with('success', __('app.label.updated_successfully', ['name' => $faq->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $faq->title]) . $th->getMessage());
        }
    }

    public function destroy(Faq $faq)
    {
        try {
            $faq->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $faq->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $faq->title]) . $th->getMessage());
        }
    }
}