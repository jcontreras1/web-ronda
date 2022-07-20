<?php

namespace App\Policies\Usuario;

use App\Models\User;
use App\Models\Usuario\UsuarioTipoUsuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioTipoUsuarioPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->id == 1){
            return true;
        }
    }

    public function create(User $user)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }

    public function delete(User $user, UsuarioTipoUsuario $usuarioTipoUsuario)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }

}
