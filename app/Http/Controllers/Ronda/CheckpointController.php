<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Checkpoint;
use App\Models\Ronda\ImageCheckpoint;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

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

		$checkpoint = Checkpoint::create([
			'latitud' => $request->latitud,
			'longitud' => $request->longitud,
			'novedad' => $novedad,
			'ronda_id' => $ronda->id,
			'user_id' => Auth::user()->id,
		]);

		if($request->hasFile('imagen')){

			$files = $request->file('imagen');
			foreach($files as $file){
				$ext = $file->getClientOriginalExtension();
				$filename = Str::uuid() . '.'. $ext;
				$resizedImage = Image::make($file);
				$resizedImage->orientate();
				if($resizedImage->height() >= $resizedImage->width()){
					/*Es mas alta que ancha, redimensiono par que sea de 720 de altura*/
					$resizedImage->heighten(720)->encode('jpg', 75);
				}else{
					$resizedImage->widen(1280)->encode('jpg', 75);
				}

				Storage::disk('public')->put('ronda/' . $checkpoint->id . '/' . $filename, $resizedImage->__toString(), 'public');

				ImageCheckpoint::create([
					'filename' => $filename,
					'checkpoint_id' => $checkpoint->id,
				]);
			}
		}
		toast('Novedad agregada', 'success')->autoClose(1500);
		return back();        
	}

	public function destroy(Request $request, Ronda $ronda, Checkpoint $checkpoint){
		$checkpoint->delete();
		toast('Novedad eliminada', 'success')->autoClose(1500);
		return back();        
	}
}
