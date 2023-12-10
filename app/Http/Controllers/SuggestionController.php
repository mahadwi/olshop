<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    public function index(Request $request)
    {
        $suggestions = Suggestion::query();
        if ($request->has('search')) {
            $suggestions->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $suggestions->orderBy($request->field, $request->order);
        }

        $suggestions->orderBy('id');
        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Suggestion/Index', [
            'title'         => 'Data '.__('app.label.suggestion'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'suggestions'         => $suggestions->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Setting', 'href' => '#'],
                ['label' => __('app.label.suggestion'), 'href' => route('suggestion.index')],
            ],
        ]);
    }   
}
