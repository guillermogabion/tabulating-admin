<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'address',
        'other'
    ];

    public function score()
    {
        return $this->belongsToMany(Score::class, 'participant_id');
    }

    public function result()
    {
        return $this->belongsTo(Result::class, 'event_id');
    }
}
