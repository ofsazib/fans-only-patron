<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // no timestamps, please
    public $timestamps = false;

    // relationship to profile
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'id', 'category_id');
    }
}
