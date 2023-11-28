<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\SubscribeSplash;
use Illuminate\Http\Request;
use App\Actions\StoreSubscribeAction;
use App\Actions\UpdateSubscribeAction;
use App\Http\Requests\SubscribeStoreRequest;
use App\Http\Requests\SubscribeUpdateRequest;

class SubscribeController extends Controller
{
    public function index(Request $request)
    {
        $subscribe = SubscribeSplash::query();
        if ($request->has('search')) {
            $subscribe->where('title', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $subscribe->orderBy($request->field, $request->order);
        }

        $subscribe->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('SubscribeSplash/Index', [
            'title'         => 'Data '.__('app.label.subscribe_splash'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'subscribes'         => $subscribe->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.subscribe_splash'), 'href' => route('subscribe.index')],
            ],
        ]);
    }

    public function store(SubscribeStoreRequest $request)
    {
        try {
            $subscribe = dispatch_sync(new StoreSubscribeAction($request->all()));

            return back()->with('success', __('app.label.created_successfully', ['name' => $subscribe->name]));

        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.subscribe_splash')]) . $th->getMessage());
        }
    }

    public function update(SubscribeUpdateRequest $request, SubscribeSplash $subscribe)
    {
        try {

            $subscribe = dispatch_sync(new UpdateSubscribeAction($subscribe, $request->all()));

            return back()->with('success', __('app.label.updated_successfully', ['name' => $subscribe->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => $subscribe->name]) . $th->getMessage());
        }
    }

    public function destroy(SubscribeSplash $subscribe)
    {
        try {
            $subscribe->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $subscribe->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $subscribe->name]) . $th->getMessage());
        }
    }
}
