<?php

namespace Monitoring\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Monitoring\Contact\Models\Contact;
use Monitoring\Contact\Mail\ContactMailable;

class ContactController extends Controller
{
   public function index()
   {
           return view('monitoring::contact');
   }

   public function send(Request $request)
   {
       Mail::to('Hambardzumyan.bakur@gmail.com')->send(new ContactMailable($request->message, $request->name));
       Contact::create($request->all());
       return redirect(route('contact'));
   }
}
