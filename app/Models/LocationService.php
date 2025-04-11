<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationService extends Model
{
    use HasFactory;
    protected $table = 'location_services';
    protected $primaryKey = 'service_id';
    protected $fillable = ["venue_id", "service_name","price","is_available","description"];
}
