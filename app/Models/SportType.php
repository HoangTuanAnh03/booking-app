<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SportType extends Model
{
    protected $table = 'sport_types';
    protected $primaryKey = 'sport_type_id';
    protected $fillable = ["name", "description"];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'sport_type_id', 'sport_type_id');
    }
}
