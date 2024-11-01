<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'role',
        'avatar',
        'cover',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



    public function stadiums()
    {
        return $this->hasMany(Stadium::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class);
    }

    public function randomMatchRequests()
    {
        return $this->hasMany(RandomMatchRequests::class);
    }

    public function matchLogs()
    {
        return $this->hasMany(MatchLog::class);
    }

    public function events()
    {
        return $this->hasMany(Events::class);
    }
    
   

    

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function messagesReceived()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
    public function allMessages($id)
    {
        $sentMessages = $this->messages()->where('receiver_id',$id)->toBase();
        $receivedMessages = $this->messagesReceived()->where('sender_id', $id)->toBase();

        return $sentMessages->union($receivedMessages)->get();
    }
    
}
