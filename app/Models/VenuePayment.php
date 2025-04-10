<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenuePayment extends Model
{
    protected $table = 'venue_payment';
    protected $primaryKey = 'id';
    protected $fillable = ["owner_id", "venue_id", "amount", "code", "message"];
    //
}
