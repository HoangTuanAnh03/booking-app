<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FieldPrice extends Model
{
    protected $table = 'field_price';
    protected $primaryKey = 'field_price_id';
    protected $fillable = ["field_id", "day_of_week","start_time","end_time", "price", "min_rental"];
}
