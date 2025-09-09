<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact(){
        $contacts = Contact::where('read_status', 'unread')->get();
        return view('Backend.Pages.Contact', compact('contacts'));
    }

    public function contactRead(){
        $contacts = Contact::where('read_status', 'read')->get();
        return view('Backend.Pages.Contact', compact('contacts'));
    }
}
