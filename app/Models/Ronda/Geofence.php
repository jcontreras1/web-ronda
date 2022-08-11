<?php

namespace App\Models\Ronda;

use App\Models\Ronda\Circuito;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geofence extends Model
{
    use HasFactory;

    protected $table = 'geofence';
    protected $fillable = 
    [
        'latitud',
        'longitud',
        'radio',
        'created_by',
        'circuito_id',
    ];

    public function Circuito(){
        return $this->belongsTo(Circuito::class);
    }
}
