<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    // has subscription
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    // has user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
