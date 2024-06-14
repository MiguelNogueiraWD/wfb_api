<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;

    //Définition des attributs et des relations
    protected $fillable = [
        'contrat_type',
        'date_debut',
        'date_fin',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
