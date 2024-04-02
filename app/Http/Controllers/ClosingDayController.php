<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\ClosingDay;
use Illuminate\Http\Request;

class ClosingDayController extends Controller
{
    public function index(Request $request)
    {
        $closings = ClosingDay::whereNotNull('close')
        ->when($request->has('search'), function ($query) use ($request) {
            $query->where('name', 'ILIKE', "%{$request->search}%");
        })
        ->orderByDesc('id');        
        
        // dd($closings->get()[0]->open_formatted);
        $perPage = $request->has('perPage') ? $request->perPage : 10;
        
        return Inertia::render('ClosingDay/Index', [
            'title'         => 'Data '.__('app.label.closing_day'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'closings'      => $closings->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.closing_day'), 'href' => route('closing-day.index')],
            ],
        ]);
    }

    public function show(ClosingDay $closing_day)
    {     
        $filter = Carbon::parse($closing_day->open)->format('Y-m-d');

        $orders = Order::whereDate('created_at', $filter)
        ->where('is_pos', true)
        ->where('is_offline', true)
        ->orderByDesc('id')
        ->get();

        return Inertia::render('ClosingDay/Show', [
            'title'         => 'Show '.__('app.label.closing_day'),
            'closingDay'    => $closing_day->load(['kasirOpen', 'kasirClose']),
            'orders'        => $orders,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.closing_day'), 'href' => route('closing-day.index')],
            ],
        ]);
    }
}
