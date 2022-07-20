<?php

namespace App\Policies\Finanzas;

use App\Models\Finanzas\Tarifa;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TarifaPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->id == 1){
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }

    public function view(User $user, Tarifa $tarifa)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }

    public function create(User $user)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }

    public function update(User $user, Tarifa $tarifa)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }

    public function delete(User $user, Tarifa $tarifa)
    {
        if($tarifa->id < 4){
            return false;
        }
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }
}
