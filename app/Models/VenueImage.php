<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VenueImage extends Model
{
    //
    protected $table = 'venue_images';

    protected $primaryKey = 'image_id';

    protected $fillable = [

        'venue_id',
        'image_url',
        'type',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id', 'venue_id');
    }
}
