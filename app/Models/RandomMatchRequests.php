<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomMatchRequests extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_date',
        'preferred_time',
        'is_available_all_days',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
