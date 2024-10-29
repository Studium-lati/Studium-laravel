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
        'user_id',
        'rating'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }   

    use HasFactory;
}
