<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    //Définition des attributs et des relations
    protected $fillable = [
        'maintenance_date',
        'maintenance_description',
        'cout',
        'bus_id'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
