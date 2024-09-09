<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status', 'event_id'];

    public function event_category()
    {
        return $this->belongsTo(Events::class, 'event_id');
    }

    public function parameter()
    {
        return $this->belongsToMany(Category::class, 'category_id');
    }

    public function score()
    {
        return $this->belongsToMany(Score::class, 'category_id');
    }

    public function result()
    {
        return $this->belongsToMany(Result::class, 'category_id');
    }
}
