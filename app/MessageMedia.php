<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageMedia extends Model
{
    public $timestamps = false;

    // relationship to message
    public function message()
    {
        return $this->belongsTo('App\Message');
    }
}
