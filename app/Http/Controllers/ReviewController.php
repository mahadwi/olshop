<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Actions\StoreReviewAction;
use App\Actions\UpdateReviewAction;
use App\Http\Requests\ReviewStoreRequest;
use App\Http\Requests\ReviewUpdateRequest;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::query();
        if ($request->has('search')) {
            $reviews->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $reviews->orderBy($request->field, $request->order);
        }

        $reviews->orderBy('id');
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Review/Index', [
            'title'         => 'Data '.__('app.label.review'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'reviews'         => $reviews->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.review'), 'href' => route('review.index')],
            ],
        ]);
    }   

    public function store(ReviewStoreRequest $request)
    {
        try {
            $review = dispatch_sync(new StoreReviewAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $review->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.review_us')]) . $th->getMessage());
        }
    }

    public function update(ReviewUpdateRequest $request, Review $review)
    {
        try {

            $review = dispatch_sync(new UpdateReviewAction($review, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $review->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $review->name]) . $th->getMessage());
        }
    }


    public function destroy(Review $review)
    {
        try {
            $review->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $review->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $review->name]) . $th->getMessage());
        }
    }
}
