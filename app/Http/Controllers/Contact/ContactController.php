<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Management\CommunicationManagement\ContactMessage\Models\Model as Contact;

class ContactController extends Controller
{
    public function contact()
    {

        return view('frontend.pages.contact.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => ['required'],
            'subject' => ['required'],
            'email' => ['email', 'required'],
            'message' => ['string', 'required'],
        ]);

        $contact_message = new Contact();
        $contact_message->full_name = request()->full_name;
        $contact_message->email = request()->email;
        $contact_message->subject = request()->subject;
        $contact_message->message = request()->message;
        $contact_message->save();

        return redirect()->back()->with('success', 'Your data has been successfully received!');
    }
}
