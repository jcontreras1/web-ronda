<?php

namespace App\Models\Usuario;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesion extends Model
{
    use HasFactory;
    protected $table = 'profesion';
    protected $fillable = ['nombre', 'descripcion'];

    public function usuarios(){
        return $this->belongsToMany(User::class, 'usuario_profesion', 'profesion_id', 'usuario_id');
    }
}
