<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferHome extends Model
{
    use HasFactory;

    protected $fillable = [
        'price',
        'description',
        'address',
        'user_id',
        'home_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }
}
