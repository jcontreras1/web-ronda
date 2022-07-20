<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UsuarioProfesion extends Pivot
{
    use HasFactory;
    protected $table = 'usuario_profesion';
    protected $fillable = ['usuario_id', 'profesion_id'];
}
