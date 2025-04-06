<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    // no timestamps, please
    public $timestamps = false;

    // casts
    public $casts = ['p_meta' => 'array'];

    // fillable
    protected $fillable = ['gateway', 'p_meta', 'is_default'];

    // belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
