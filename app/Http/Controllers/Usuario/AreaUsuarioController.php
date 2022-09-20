<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Varios\AreaUsuario;
use Illuminate\Http\Request;

class AreaUsuarioController extends Controller
{
	public function store(User $user, Request $request){
		$es_jefe = $request->has('es_jefe');
		AreaUsuario::updateOrCreate([
			'user_id' => $user->id,
			'area_id' => $request->area_id,
		],[
			'es_jefe' => $es_jefe,
		]);

		toast('Area agregada', 'success')->autoClose(1500);
		return back();
	}

	public function destroy(User $user, AreaUsuario $area_usuario){
		$area_usuario->delete();
		toast('Area eliminada', 'success')->autoClose(1500);
		return back();
	}
}
