<?php

namespace App\Models\Varios;

use App\Models\Ronda\Circuito;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = "area";
    protected $fillable = ['nombre'];

    public function circuitos(){
        return $this->hasMany(Circuito::class);
    }

    public function usuarios(){
        return $this->belongsToMany(User::class, 'area_usuario', 'area_id', 'user_id')->withPivot('id', 'es_jefe');
    }
}
