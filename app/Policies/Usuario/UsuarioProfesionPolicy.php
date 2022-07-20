<?php

namespace App\Policies\Usuario;

use App\Models\User;
use App\Models\Usuario\UsuarioProfesion;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioProfesionPolicy
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


    public function delete(User $user, UsuarioProfesion $usuarioProfesion)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }
}
