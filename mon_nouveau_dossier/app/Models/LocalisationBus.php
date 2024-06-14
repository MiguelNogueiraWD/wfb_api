<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalisationBus extends Model
{
    use HasFactory;

    //Définition des attributs et des relations
    protected $fillable = [
        'latitude',
        'longitude',
        'date_heure'
    ];
}
