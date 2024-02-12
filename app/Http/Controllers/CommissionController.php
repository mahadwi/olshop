<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Commission;
use Illuminate\Http\Request;

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
       
        return Inertia::render('Color/Index', [
            'title'         => 'Data '.__('app.label.color'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'commissions'         => $commissions->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.color'), 'href' => route('commission.index')]
            ],
        ]);
    }   
}
