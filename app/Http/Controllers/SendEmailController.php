<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ReplyMessageEmail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\SendEmailRequest;

class SendEmailController extends Controller
{
    public function send(SendEmailRequest $request)
    {
        $param = [
           'to' => $request['email'],
           'subject' => 'Reply Message',
           'message' => $request->post('message')
        ];        
        
        Mail::to($param['to'])->send(new ReplyMessageEmail($param));

        return back()->with('success', 'Success');
    }
}
