<?php

namespace App\Policies\Varios;

use App\Models\User;
use App\Models\Varios\Color;
use Illuminate\Auth\Access\HandlesAuthorization;

class ColorPolicy
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

    public function create(User $user)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }

    public function delete(User $user, Color $color)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }
}
