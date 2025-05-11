<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class bookingCourt extends Model
{
    protected $table = 'booking_courts';

    protected $primaryKey = 'booking_court_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'booking_id',
        'court_id',
        'start_time',
        'end_time',
        'price',
    ];

    // Quan hệ: BookingCourt thuộc về Court
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'court_id', 'court_id');
    }

    // Quan hệ: BookingCourt có nhiều CourtSlot
    public function courtSlots(): HasMany
    {
        return $this->hasMany(CourtSlot::class, 'booking_court_id', 'booking_court_id');
    }
}
