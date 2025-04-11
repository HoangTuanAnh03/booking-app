<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldOpeningHours extends Model
{
    protected $table = 'field_opening_hours';
    protected $primaryKey = 'opening_id';
    protected $fillable = ["field_id", "day_of_week","opening_time","closing_time"];
}
