<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Brand;
use App\Models\Commission;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $commissions = Commission::query();
        if ($request->has('search')) {
            $commissions->where('brand_id', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $commissions->orderBy($request->field, $request->order);
        }

        $commissions->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Commission/Index', [
            'title'         => 'Data '.__('app.label.commission'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'commissions'         => $commissions->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.commission'), 'href' => route('commission.index')]
            ],
        ]);
    }   

    public function create()
    {
        $brands = Brand::get();
        $categories = ProductCategory::active()->get();
        
        return Inertia::render('Commission/Create', [
            'title'         => 'Create '.__('app.label.commission'),
            'categories'    => $categories,
            'brands'       => $brands,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.commission'), 'href' => route('commission.index')],
            ],
        ]);
    }
}
