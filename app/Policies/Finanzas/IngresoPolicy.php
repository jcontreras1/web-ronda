<?php

namespace App\Policies\Finanzas;

use App\Models\Finanzas\Ingreso;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IngresoPolicy
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
    
    public function update(User $user, Ingreso $ingreso)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS', 'CONTABLE'], $user->tipos_usuario);
    }

    public function delete(User $user, Ingreso $ingreso)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }
}
