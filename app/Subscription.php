<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // no timestamps, please
    public $timestamps = false;



    protected $casts = [
        'subscription_date' => 'datetime',
        'subscription_expires' => 'datetime'
    ];

    // belongs to creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    // belongs to subscriber
    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id', 'id');
    }
}
