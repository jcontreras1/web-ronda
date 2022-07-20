<?php

namespace App\Models\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioTipoUsuario extends Model
{
    use HasFactory;
    protected $table = 'usuario_tipo_usuario';
    protected $fillable = ['usuario_id', 'tipo_usuario_id'];
}
