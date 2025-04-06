<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    // auth middleware
    public function __construct()
    {
        $this->middleware('auth');
    }

    // billing history
    public function history()
    {
        // get user invoices
        $invoices = auth()->user()->invoices()->with('subscription.creator')->orderByDesc('id')->paginate(10);

        return view('billing.history', compact('invoices'));
    }

    // cards
    public function cards()
    {
        return view('billing.cards');
    }
}
