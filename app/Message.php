<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Message extends Model
{
    public function receiver()
    {
        return $this->hasOne(User::class, 'id', 'to_id');
    }

    public function sender()
    {
        return $this->hasOne(User::class, 'id', 'from_id');
    }

    public function media()
    {
        return $this->hasMany('App\MessageMedia');
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

}
