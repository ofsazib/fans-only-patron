<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tips extends Model
{
    // relation to post
    public function post()
    {
        return $this->hasOne(Post::class, 'id');
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
