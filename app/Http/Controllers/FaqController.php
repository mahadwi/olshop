<?php

namespace App\Http\Controllers;

use App\Models\FaqQuestionAnswer;
use App\Models\FaqSection;
use App\Models\FaqImage;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\FaqStoreRequest;
use App\Http\Requests\FaqUpdateRequest;
use App\Actions\StoreFaqAction;
use App\Actions\StoreImageFaqAction;
use App\Actions\UpdateFaqAction;

class FaqController extends Controller
{

    public function index(Request $request)
    {
        $faqs = FaqQuestionAnswer::query();
        $faqSection = FaqQuestionAnswer::query()
            ->join('faq_sections', 'faq_question_answers.faq_section_id', '=', 'faq_sections.id')
            ->select('faq_question_answers.*', 'faq_sections.section', 'faq_sections.section_en')
            ->get();
        $faqImage = FaqImage::get();

        if ($request->has('search')) {
            $faqs->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $faqs->orderBy($request->field, $request->order);
        }

        $faqs->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        $faqs = $faqs->paginate($perPage);

        return Inertia::render('Faq/Index', [
            'title'         => 'Data '.__('app.label.faq'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'faqs'       => $faqs,
            'faqSection'       => $faqSection,
            'faqImages' => $faqImage,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.faq'), 'href' => route('faq.index')],
            ],
        ]);
    }

    public function store(FaqStoreRequest $request)
    {
        try {
            $params = [
                'faq_section_id' => $request->section,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'description' => $request->description,
                'description_en' => $request->description_en,
            ];

            $faq = dispatch_sync(new StoreFaqAction($params));

            return back()->with('success', __('app.label.created_successfully', ['name' => $faq->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.faq')]) . $th->getMessage());
        }
    }

    public function update(FaqUpdateRequest $request, FaqQuestionAnswer $faq)
    {
        try {
            $params = [
                'faq_section_id' => $request->section,
                'title' => $request->title,
                'title_en' => $request->title_en,
                'description' => $request->description,
                'description_en' => $request->description_en,
            ];

            $faq = dispatch_sync(new UpdateFaqAction($faq, $params));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $faq->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $faq->title]) . $th->getMessage());
        }
    }

    public function destroy(FaqQuestionAnswer $faq)
    {
        try {
            $faq->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $faq->title]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $faq->title]) . $th->getMessage());
        }
    }

    public function getFaqSection() {
        $faqSection = FaqSection::get();
        return json_encode($faqSection);
    }

    public function faqImage(Request $request, FaqImage $faqImage) {
        try {
            $validatedData = $request->validate([
                'image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            ]);
            $params = [
                'image' => $request->image,
            ];
            $faqId = $faqImage->find(1);
            $faqImage = dispatch_sync(new StoreImageFaqAction($faqId, $params));

            return back()->with('success', __('app.label.updated_successfully', ['name' => 'Image']));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => 'Image']) . $th->getMessage());
        }

    }
}
