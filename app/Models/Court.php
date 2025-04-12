<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $table = 'court';
    protected $primaryKey = 'court_id';
    protected $keyType = 'string';

    protected $fillable = [
        'court_id',
        'field_id',
        'court_name',
        'is_active',
    ];

    // Quan hệ: Court thuộc về Field
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id', 'field_id');
    }

    // Quan hệ: Court có nhiều CourtSlot
    public function courtSlots(): HasMany
    {
        return $this->hasMany(CourtSlot::class, 'court_id', 'court_id');
    }

}
