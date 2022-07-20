<?php

namespace App\Policies\Empresa;

use App\Models\Empresa\Salida;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalidaPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if($user->id == 1){
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Empresa\Salida  $salida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Salida $salida)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Empresa\Salida  $salida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Salida $salida)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Empresa\Salida  $salida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Salida $salida)
    {
        if(count($salida->pasajeros) > 0){
            return false;
        }
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Empresa\Salida  $salida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Salida $salida)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Empresa\Salida  $salida
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Salida $salida)
    {
        if(count($salida->pasajeros) > 0){
            return false;
        }
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }
}
