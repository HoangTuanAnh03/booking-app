<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $table = 'fields';
    protected $primaryKey = 'field_id';
    protected $fillable = ["field_id","venue_id", "sport_type_id","field_name","default_price","is_active"];
}
