<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Venue extends Model
{
    use HasFactory;
    use HasSpatial;
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

    protected array $spatialFields = ['coordinates'];

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

    public function payments(): HasMany
    {
        return $this->hasMany(VenuePayment::class, 'venue_id', 'venue_id');
    }
}
