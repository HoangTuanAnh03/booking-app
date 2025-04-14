<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courtSlot extends Model
{
    protected $table = 'court_slots';
    protected $primaryKey = 'slot_id';
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = [
        'slot_id',
        'court_id',
        'booking_court_id',
        'start_time',
        'end_time',
        'is_looked',
        'locked_by_owner',
    ];

    public $timestamps = false;

    // Quan hệ: CourtSlot thuộc về Court
    public function court(): BelongsTo
    {
        return $this->belongsTo(Court::class, 'court_id', 'court_id');
    }

    // Quan hệ: CourtSlot thuộc về BookingCourt
    public function bookingCourt(): BelongsTo
    {
        return $this->belongsTo(BookingCourt::class, 'booking_court_id', 'booking_court_id');
    }
}
