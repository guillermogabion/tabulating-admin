<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_id',
        'message',
        'status',
        'reason',
        'subject'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function requestTo()
    {
        return $this->belongsTo(User::class, 'requested_id');
    }
}
