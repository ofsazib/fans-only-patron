<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    // no timestamps, please
    public $timestamps = false;

    public $fillable = ['amount', 'created_at'];

    public $casts = ['created_at' => 'datetime'];

    public function getformattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    // relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
