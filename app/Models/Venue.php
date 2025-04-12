<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Venue extends Model
{
    use HasFactory;

    protected $casts = [
        'coordinates' => Point::class,
    ];

    protected $table = 'venues';
    protected $primaryKey = 'venue_id';
    protected $keyType = 'string';
    protected $fillable = [
        'venue_id',
        'owner_id',
        'name',
        'address',
        'longitude',
        'latitude',
        'coordinates',
        'bank_account_number',
        'bank_name',
        'status',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(VenueImage::class, 'venue_id', 'venue_id');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'venue_id', 'venue_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'uuid');
    }
}
