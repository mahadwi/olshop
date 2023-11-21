<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::query();
        if ($request->has('search')) {
            $contacts->where('telp', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $contacts->orderBy($request->field, $request->order);
        }

        $contacts->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;
       
        return Inertia::render('Contact/Index', [
            'title'         => 'Data '.__('app.label.contact'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'contacts'         => $contacts->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => ''],
                ['label' => __('app.label.contact'), 'href' => route('contact.index')]
            ],
        ]);
    }    

    public function store(ContactRequest $request)
    {
        try {

            $contact = new Contact($request->all());
            $contact->save();
            
            return back()->with('success', __('app.label.created_successfully', ['name' => $contact->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.contact')]) . $th->getMessage());
        }
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        try {            
            $contact->fill($request->all())->save();
            return back()->with('success', __('app.label.updated_successfully', ['name' => 'Contact']));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.updated_error', ['name' => 'Contact']) . $th->getMessage());
        }
    }

    public function destroy(Contact $contact)
    {   
        try {
            $contact->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $contact->name]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $contact->name]) . $th->getMessage());
        }
    }
}
