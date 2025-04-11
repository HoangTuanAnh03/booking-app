<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportType extends Model
{
    protected $table = 'sport_types';
    protected $primaryKey = 'sport_type_id';
    protected $fillable = ["name", "description"];
}
