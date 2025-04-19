<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VenuePayment extends Model
{
    protected $table = 'venue_payment';
    protected $primaryKey = 'id';
    protected $fillable = ["owner_id", "venue_id", "amount", "code", "message"];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'uuid');
    }
}
