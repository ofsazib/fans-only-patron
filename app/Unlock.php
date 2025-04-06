<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unlock extends Model
{
    // relation to message
    public function message()
    {
        return $this->hasOne(Message::class, 'id');
    }

    // relation to tipper
    public function tipper()
    {
        return $this->belongsTo(User::class, 'tipper_id');
    }

    // relation to tipper
    public function tipped()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
