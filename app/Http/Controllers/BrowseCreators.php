<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;

class BrowseCreators extends Controller
{
    // browse creators
    public function browse(Request $r)
    {
        // redirect to login if setting says No
        if(opt('allow_guest_creators_view', 'Yes') == 'No' && !auth()->check())
            return redirect(route('login'));

        // get route params
        $routeParams = $r->route()->parameters;

        // set default category
        $category = 'all';

        // do we need to have a category?
        if (array_key_exists('category', $routeParams))
            $category = (int) $routeParams['category'];

        return view('creators.browse', compact('category'));
    }
}
