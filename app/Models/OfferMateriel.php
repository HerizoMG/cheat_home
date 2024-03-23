<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferMateriel extends Model
{
    use HasFactory;

    protected $fillable = [
        'materiel_id',
        'user_id',
        'description',
    ];
    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
