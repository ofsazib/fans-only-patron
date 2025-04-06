<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{

    // only authenticated users
    public function __construct()
    {
        $this->middleware('auth');
    }


    // index
    public function index()
    {
        return view('profile.notifications');
    }
}
