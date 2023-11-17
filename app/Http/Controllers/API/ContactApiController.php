<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactApiController extends Controller
{
    public function index()
    {
        $contact = Contact::get();

        return $this->apiSuccess($contact);
    }
}
