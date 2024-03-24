<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'lastname',
        'phone',
        'address',
        'roles',
        'avatar',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function home()
    {
        return $this->hasMany(Home::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function location()
    {
        return $this->hasMany(Location::class);
    }

    public function offerHome()
    {
        return $this->hasMany(OfferHome::class);
    }

    public function offerMateriel()
    {
        return $this->hasMany(OfferMateriel::class);
    }

    public function interested()
    {
        return $this->hasMany(Interested::class);
    }
}
