<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vol extends Model
{
    use HasFactory;

    //Définition des attributs et des relations
    protected $fillable = [
        'provenance',
        'destination',
        'heure_arrivee',
        'heure_depart'
    ];

    public function missions()
    {
        return $this->hasMany(Mission::class);
    }
}
