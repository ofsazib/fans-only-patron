<?php

namespace App\Http\Controllers;

use App\Mail\NewContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    // contact form
    public function contact_page()
    {
        
        // set random numbers
        $no1 = rand(1,5);
        $no2 = rand(1,5);
        
        // get total
        $total = $no1+$no2;

        // put in session
        session(['total' => $total]);

        return view('contact-page', compact('no1', 'no2'));

    }

    // process contact form
    public function contact_form_process(Request $r)
    {

        // validate the form
        $this->validate($r, [
            'your_name' => 'required|min:2',
            'your_email' => 'required|email',
            'your_subject' => 'required|min:2',
            'your_message' => 'required|min:5',
            'reported_math' => 'required|numeric'
        ]);

        // detect bots
        if($r->the_field AND !empty($r->the_field)) {
            alert()->info(__('v14.bot-in-report-form'), __('v14.bot'));
            return back();
        }

        // check total
        if(!session('total')) {
            alert()->info(__('v14.direct-access'), __('v14.bot'));
            return back();
        }

        // check math answer
        if(  $r->reported_math != session('total') ) {
            alert()->info(__('v14.wrong-math'));
            return back()->withInput();
        }

        // finally, all seems legit - notify admin via email
        
        // notify admin of a new email report
        Mail::to(opt('admin_email'))->send(new NewContactFormMail($r->your_name, $r->your_subject, $r->your_email, $r->your_message));

        alert()->info(__('v18.thanks-for-getting-in-touch'));

        return redirect(route('home'));
    
    }

}
