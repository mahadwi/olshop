<?php

namespace App\Http\Controllers;

use App\Models\EventDetail;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EventDetailController extends Controller
{
    public function show(EventDetail $eventDetail)
    {   
        return Inertia::render('EventDetail/Show', [
            'title'             => 'Detail '.__('app.label.ticket'),
            'eventDetail'         => $eventDetail->load('bookings.paymentable', 'bookings.user'),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }
}
