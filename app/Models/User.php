<?php

namespace App\Models;

use App\Models\Usuario\TipoUsuario;
use App\Models\Usuario\UsuarioTipoUsuario;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'dni',
        'password',
        'fecha_nacimiento',
        'usuario_sistegral',
        'telefono',
        'sync',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tipos_usuario(){
        return $this->belongsToMany(TipoUsuario::class, 'usuario_tipo_usuario', 'usuario_id', 'tipo_usuario_id')->withPivot('id');
    }
}
