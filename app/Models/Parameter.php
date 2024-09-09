<?php

namespace App\Models;

use Illuminate\Console\Scheduling\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'minimum',
        'maximum'
    ];

    public function category_parameter()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
