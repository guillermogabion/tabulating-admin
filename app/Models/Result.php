<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'category_id',
        'event_id',
        'result'
    ];

    public function result_participant()
    {
        return $this->belongsTo(Participants::class, 'participant_id');
    }

    public function category_result()
    {
        return $this->belongsTo(Category::class,  'category_id', 'id');
    }

    public function event_result()
    {
        return $this->hasMany(Events::class, 'id', 'event_id');
    }
}
