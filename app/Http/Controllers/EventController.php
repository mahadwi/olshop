<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Actions\StoreEventAction;
use App\Constants\TicketCapacity;
use App\Actions\UpdateEventAction;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;

class EventController extends Controller
{
    
    public function __construct()
    {
        $this->capacity = TicketCapacity::getValues();
    }

    public function index(Request $request)
    {
        $events = Event::query();
        
        if ($request->has('search')) {

            $events->where('name', 'ILIKE', "%" . $request->search . "%");
            // ->orWhereHas('brand', function($q) use ($request){
            //     $q->where('name', 'ILIKE', "%" . $request->search . "%");
            // });
            // $events->orWhere('no_hp', 'LIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $events->orderBy($request->field, $request->order);
        }

        $perPage = $request->has('perPage') ? $request->perPage : 10;
                
        return Inertia::render('Event/Index', [
            'title'         => 'Data '.__('app.label.event'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'events'      => $events->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }

    public function create()
    {   
        return Inertia::render('Event/Create', [
            'title'         => 'Create '.__('app.label.event'),
            'capacity'      => $this->capacity,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }
    
    public function store(EventStoreRequest $request)
    {
        try {
            $event = (new StoreEventAction($request->all()))->handle(); 
            return redirect()->route('event.index')->with('success', __('app.label.created_successfully', ['name' => $event->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.event')]) . $th->getMessage());
        }
    }

    public function edit(Event $event)
    {   
        return Inertia::render('Event/Edit', [
            'title'             => 'Edit '.__('app.label.event'),
            'event'         => $event->load('details'),
            'capacity'      => $this->capacity,
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.event'), 'href' => route('event.index')],
            ],
        ]);
    }


    public function update(EventUpdateRequest $request, Event $event)
    {
        try {
            $event = (new UpdateEventAction($event, $request->all()))->handle(); 

            return redirect()->route('event.index')->with('success', __('app.label.updated_successfully', ['name' => $event->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $event->name]) . $th->getMessage());
        }
    }


    public function destroy(Event $event)
    {
        try {
            
            $event->details()->delete();
            
            $event->delete();

            return back()->with('success', __('app.label.deleted_successfully', ['name' => $event->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $event->name]) . $th->getMessage());
        }
    }
}
