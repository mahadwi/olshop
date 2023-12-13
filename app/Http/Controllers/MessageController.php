<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Requests\MessageUpdateRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmailMessageCustomer;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $message = Message::query();
        if ($request->has('search')) {
            $message->where('name', 'ILIKE', "%" . $request->search . "%");
        }
        if ($request->has(['field', 'order'])) {
            $message->orderBy($request->field, $request->order);
        }

        $message->orderBy('id');

        $perPage = $request->has('perPage') ? $request->perPage : 10;

        return Inertia::render('Message/Index', [
            'title'         => 'Data '.__('app.label.message'),
            'filters'       => $request->all(['search', 'field', 'order']),
            'perPage'       => (int) $perPage,
            'messages'         => $message->paginate($perPage),
            'breadcrumbs'   => [
                ['label' => 'Data Master', 'href' => '#'],
                ['label' => __('app.label.message'), 'href' => route('message.index')],
            ],
        ]);
    }

    public function update(MessageUpdateRequest $request, Message $message)
    {
        try {
            $params = ['reply_message' => $request->reply_message];
            $message->update($params);

            $checkEmail = Validator::make(['email' => $request->email], [
                'email' => 'required|email:rfc,dns',
            ]);

            if (!$checkEmail->fails()) {
                $dataParams = [
                    'to' => $request->email,
                    'subject' => '[REPLY]' . ' '. $request->subject,
                    'message' => $request->reply_message
                ];
                 Mail::to($dataParams['to'])->queue(new SendEmailMessageCustomer($dataParams));
             }else {
                 return back()->with('error', __('app.label.created_error', ['name' => __('app.label.format_email_wrong')]). $request->email);
             }
            return back()->with('success', __('app.label.created_successfully', ['name' => $message ->reply_message]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.created_error', ['name' => __('app.label.message')]) . $th->getMessage());
        }
    }

    public function destroy(Message $message)
    {
        try {
            $message->delete();
            return back()->with('success', __('app.label.deleted_successfully', ['name' => $message->subject]));
        } catch (\Throwable $th) {
            return back()->with('error', __('app.label.deleted_error', ['name' => $message->subject]) . $th->getMessage());
        }
    }
}
