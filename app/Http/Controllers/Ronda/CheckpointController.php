<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Checkpoint;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckpointController extends Controller
{
	public function store(Request $request, Ronda $ronda){
		$request->validate([
			'latitud' => 'required',
			'longitud' => 'required',
		]);
		$novedad = $request->novedad;
		if(trim($request->novedad) == "")
			$novedad = "Sin novedades";

		Checkpoint::create([
			'latitud' => $request->latitud,
			'longitud' => $request->longitud,
			'novedad' => $novedad,
			'ronda_id' => $ronda->id,
			'user_id' => Auth::user()->id,
		]);

		toast('Novedad agregada', 'success')->autoClose(1500);
		return back();        
	}

	public function destroy(Request $request, Ronda $ronda, Checkpoint $checkpoint){
		$checkpoint->delete();
		toast('Novedad eliminada', 'success')->autoClose(1500);
		return back();        
	}
}
