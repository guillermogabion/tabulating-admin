<?php

namespace App\Models;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    public function category()
    {
        return $this->belongsToMany(Event::class, 'event_id');
    }
    public function result()
    {
        return $this->belongsToMany(Event::class, 'event_id');
    }
}
