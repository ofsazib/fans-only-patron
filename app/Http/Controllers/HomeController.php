<?php

namespace App\Http\Controllers;

use App\Report;
use App\Profile;
use App\Mail\NewContentReportMail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{

    // GET /
    public function index()
    {
        
        // lock homepage for guests?
        if(!auth()->check() AND opt('lock_homepage', 'No') == 'Yes')
            return redirect('login');

        // get a list of all creators, sorted by popularity
        $creators = Profile::where('isVerified', 'Yes')
            ->with('category')
            ->whereHas('category')
            ->orderByDesc('popularity')
            ->withCount('posts')
            ->take((int)opt('homepage_creators_count'));

        // if hide admin from creators
        if (opt('hide_admin_creators', 'No') == 'Yes') {
            $creators->join('users', 'creator_profiles.user_id', 'users.id')
                     ->where('users.isAdmin', '!=', 'Yes');
        }

        $creators = $creators->get();

        return view('homepagev2', compact('creators'));
    }

    // GET /report-content
    public function report()
    {
        // set random numbers
        $no1 = rand(1,5);
        $no2 = rand(1,5);
        
        // get total
        $total = $no1+$no2;

        // put in session
        session(['total' => $total]);

        return view('report-content-form', compact('no1', 'no2'));
    }

    // store report in db and notify admin
    public function storeReport(Request $r)
    {
        
        // validate the form
        $this->validate($r, [
            'reporter_name' => 'required|min:2',
            'reporter_email' => 'required|email',
            'reported_url' => 'required|url',
            'reported_math' => 'required|numeric',
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

        // finally, all seems legit - store the report
        $report = new Report;
        $report->reporter_name = $r->reporter_name;
        $report->reporter_email = $r->reporter_email;
        $report->reported_url = $r->reported_url;
        $report->report_message = $r->report_message;
        $report->reporter_ip = $r->ip();
        $report->save();

        // notify admin of a new email report
        Mail::to(opt('admin_email'))->send(new NewContentReportMail($report));

        alert()->info(__('v14.thanks-for-the-report'));

        return back();

    }

    // set entry popup cookie
    public function entryPopupCookie(Request $r)
    {
        //Call the withCookie() method with the response method
        return response()->json(['success' => 'cookie-set'], 200)
                        ->withCookie(cookie()->forever('entryConfirmed', now()));
    }

}
