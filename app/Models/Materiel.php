<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'price',
        'image',
        'libelle',
        'isPublished',
    ];
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    
    public function offerMateriel()
    {
        return $this->hasMany(OfferMateriel::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
