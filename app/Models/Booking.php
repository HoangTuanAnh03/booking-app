<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'booking_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'booking_id',
        'field_id',
        'user_id',
        'total_price',
        'customer_name',
        'customer_phone',
        'status',
        'booking_date'
    ];
    public function bookingCourts(): HasMany
    {
        return $this->hasMany(BookingCourt::class, 'booking_id', 'booking_id');
    }

    public function field(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }
}
