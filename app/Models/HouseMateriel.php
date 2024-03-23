<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseMateriel extends Model
{
    use HasFactory;

    protected $fillable = [
        'materiel_id',
        'isActivated',
        'home_id',
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function home()
    {
        return $this->belongsTo(Home::class);
    }
}
