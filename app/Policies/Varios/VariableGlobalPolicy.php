<?php

namespace App\Policies\Varios;

use App\Models\User;
use App\Models\Varios\VariableGlobal;
use Illuminate\Auth\Access\HandlesAuthorization;

class VariableGlobalPolicy
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

	public function view(User $user, VariableGlobal $variableGlobal)
	{
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	public function create(User $user)
	{
		return false;
	}

	public function update(User $user, VariableGlobal $variableGlobal)
	{
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	public function delete(User $user, VariableGlobal $variableGlobal)
	{
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}
}
