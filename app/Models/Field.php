<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Field extends Model
{
    protected $table = 'fields';
    protected $primaryKey = 'field_id';
    protected $fillable = ["field_id","venue_id", "sport_type_id","field_name","default_price","is_active"];
    protected $casts = [
        'field_id' => 'string',
    ];

    /**
     * @return HasOne
     */
    public function openingHourToday(): HasOne
    {
        $today = strtolower(Carbon::now()->format('l'));

        return $this->hasOne(FieldOpeningHours::class, 'field_id', 'field_id')
            ->select('field_id', 'day_of_week', 'opening_time', 'closing_time')
            ->where('day_of_week', $today);
    }

    /**
     * @return HasMany
     */
    public function openingHoursWeek(): HasMany
    {
        return $this->hasMany(FieldOpeningHours::class, 'field_id', 'field_id')
            ->select('field_id', 'day_of_week', 'opening_time', 'closing_time');
    }

    public function sportType(): BelongsTo
    {
        return $this->belongsTo(SportType::class, 'sport_type_id', 'sport_type_id');
    }
    public function courts(): HasMany
    {
        return $this->hasMany(Court::class, 'field_id', 'field_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'field_id', 'field_id');
    }
}
