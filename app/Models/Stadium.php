<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    public $fillable = [
        'name',
        'location',
        'latitude',
        'longitude',
        'price_per_hour',
        'capacity',
        'image',
        'description',
        'status',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    use HasFactory;
}