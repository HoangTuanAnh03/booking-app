<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldOpeningHours extends Model
{
    protected $table = 'field_opening_hours';
    protected $primaryKey = 'opening_id';
    protected $fillable = ["field_id", "day_of_week","opening_time","closing_time"];

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }
}
