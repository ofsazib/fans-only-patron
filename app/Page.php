<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // fillable
    protected $fillable = ['page_title', 'page_content', 'page_slug'];

    public function getRouteKeyName()
    {
        return 'page_slug';
    }

    public static function slug(Page $p)
    {
        return route('page', ['page' => $p]);
    }
}
