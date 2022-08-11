<?php

namespace App\Models\Ronda;

use App\Models\Ronda\Geofence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuito extends Model
{
    use HasFactory;

    protected $table = 'circuito';
    protected $fillable = 
    [
        'titulo',
        'descripcion',
        'created_by',
    ];

    public function geofences(){
        return $this->hasMany(Geofence::class);
    }

    public function creador(){
        return $this->belongsTo(User::class, 'created_by');
    }
}
