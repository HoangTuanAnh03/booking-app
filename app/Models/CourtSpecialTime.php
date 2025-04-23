<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtSpecialTime extends Model
{
    protected $table = 'court_special_time';
    protected $primaryKey = 'court_special_time_id';
    public $incrementing = true;
    protected $fillable = [
        'court_id',
        'date',
        'start_time',
        'end_time',
        'price',
        'min_rental'
    ];
}
