<?php

namespace App\Policies\Finanzas;

use App\Models\Empresa\Voucher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
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
        return evaluar_permisos(['ADM_SIS', 'VENTAS', 'CONTABLE'], $user->tipos_usuario);
    }

    public function view(User $user, Voucher $voucher)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS', 'CONTABLE'], $user->tipos_usuario);
    }

    public function create(User $user)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    public function update(User $user, Voucher $voucher)
    {
        return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
    }

    public function delete(User $user, Voucher $voucher)
    {
        return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
    }
}
