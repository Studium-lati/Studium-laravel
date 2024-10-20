<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'stadium_id',
        'user_id',
        'image',
    ];


    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
