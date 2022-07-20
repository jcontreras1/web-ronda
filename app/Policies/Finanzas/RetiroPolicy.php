<?php

namespace App\Policies\Finanzas;

use App\Models\Finanzas\Retiro;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RetiroPolicy
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
        return evaluar_permisos(['ADM_SIS', 'VENTAS', 'CONTABLE'], $user->tipos_usuario);
    }

    
    public function update(User $user, Retiro $retiro)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS', 'CONTABLE'], $user->tipos_usuario);
    }

    public function delete(User $user, Retiro $retiro)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario); 
    }
}
