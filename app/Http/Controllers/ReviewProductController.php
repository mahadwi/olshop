<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\OrderReview;
use Illuminate\Http\Request;

class ReviewProductController extends Controller
{
    public function index(Request $request)
    {
        $reviews = OrderReview::with(['user', 'imageable', 'product']);

        if ($request->has('search')) {
            $reviews->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $reviews->orderBy($request->field, $request->order);
        }

        $reviews->orderByDesc('id');
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('ReviewProduct/Index', [
            'title'         => 'Data '.__('app.label.review_product'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'reviews'         => $reviews->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Master', 'href' => '#'],
                ['label' => __('app.label.review_product'), 'href' => route('review-product.index')],
            ],
        ]);
    }

    public function destroy(OrderReview $review_product)
    {
        try {
            $review_product->imageable()->delete();
            $review_product->delete();
            return back()->with('success', 'Success');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}
