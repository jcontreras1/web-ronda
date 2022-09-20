<?php

namespace App\Policies\Usuario;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
		if(evaluar_permisos(['ADM_SIS'], $user->tipos_usuario)){
			return true;
		}else{
			foreach($user->areas as $area){
				if($area->pivot->es_jefe){
					/*Es jefe de algún área*/
					return true;
				}
			}
			return false;
		}
	}
	
	public function profile(User $user, User $model)
	{
		return ($user->id == $model->id);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\User  $model
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function view(User $user, User $model)
	{
		if($model->id == 1){
			return false;
		}
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	/**
	 * Determine whether the user can create models.
	 *
	 * @param  \App\Models\User  $user
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function create(User $user)
	{
		if(evaluar_permisos(['ADM_SIS'], $user->tipos_usuario)){
			return true;
		}else{
			foreach($user->areas as $area){
				if($area->pivot->es_jefe){
					/*Es jefe de algún área*/
					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\User  $model
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function update(User $user, User $model)
	{
		if($model->id == 1){
			return false;
		}

		/*REviso las areas del usuario autenticado (y si es jefe). En caso afirmativo, veo si el área del usuario a modificar coincide*/
		$areas_del_usuario_a_modificar = $model->areas;
		$areas_mias_donde_soy_jefe = $user->areas()->wherePivot('es_jefe', true)->get();
		foreach($areas_mias_donde_soy_jefe as $area_propia){
			foreach($areas_del_usuario_a_modificar as $area){
				if($area->id == $area_propia->id){
					return true;
				}
			}
		}
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\User  $model
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function delete(User $user, User $model)
	{
		if($model->id == 1){
			return false;
		}
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\User  $model
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function restore(User $user, User $model)
	{
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param  \App\Models\User  $user
	 * @param  \App\Models\User  $model
	 * @return \Illuminate\Auth\Access\Response|bool
	 */
	public function forceDelete(User $user, User $model)
	{
		if($model->id == 1){
			return false;
		}
		return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
	}
}
