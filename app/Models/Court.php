<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Court extends Model
{
    protected $table = 'courts';
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
    public function slots(): HasMany
    {
        return $this->hasMany(CourtSlot::class, 'court_id', 'court_id');
    }
}
