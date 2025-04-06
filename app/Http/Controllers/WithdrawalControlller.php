<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WithdrawalControlller extends Controller
{
    // only authenticated users
    public function __construct()
    {
        $this->middleware('auth');
    }


    // list widthdrawals
    public function index()
    {
        if (auth()->user()->profile->isVerified != 'Yes')
            abort(403);

        $active = 'withdraw';

        return view('withdrawals.index', compact('active'));
    }
}
