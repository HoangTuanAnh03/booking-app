<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'review_id';
    protected $fillable = ["venue_id", "user_id","rating","comment"];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }
}
