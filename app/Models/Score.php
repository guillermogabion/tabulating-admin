<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'participant_id',
        'categoty_id',
        'score'
    ];

    public function score_category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function score_participant()
    {
        return $this->belongsTo(Participants::class, 'participant_id');
    }
    public function score_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
