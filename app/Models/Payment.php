<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    protected $fillable = ["payment_id", "booking_id", "uid", "amount", "payment_date", "message", "status"];
    protected $casts = [
        'payment_id' => 'string',
    ];
}
