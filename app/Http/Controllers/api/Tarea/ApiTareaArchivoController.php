<?php

namespace App\Http\Controllers\api\Tarea;

use App\Models\Tarea\Tarea;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tarea\TareaArchivo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiTareaArchivoController extends Controller
{
    public function index(Tarea $tarea){
        return response($tarea->archivos, 200);
    }

    public function store(Tarea $tarea, Request $request){
        $archivo = null;
        if($request->hasFile('doc')){
            $file = $request->file('doc');
            $originalFileName = $file->getClientOriginalName();
            $filename = Str::uuid() . '.' . $request->file('doc')->extension();
            /* Guardo el archivo */
            Storage::disk('local_tareas')->putFileAs($tarea->id, $file, $filename);
            /* Guardo en la base de datos */
            $formato_imagen = $request->file('doc')->extension();
            if(in_array($formato_imagen, formatos_imagen())){
                $formato_imagen = 'img';
            }else{
                $admisibles = ['doc', 'docx', 'txt', 'exe', 'zip', 'rar', 'pdf', 'xls', 'xlsx'];
                if(!in_array($formato_imagen, $admisibles)){
                    $formato_imagen = 'unk';
                }else{
                    $formato_imagen = str_replace('docx', 'doc', $formato_imagen);
                    $formato_imagen = str_replace('rar', 'zip', $formato_imagen);
                    $formato_imagen = str_replace('xlsx', 'xls', $formato_imagen);
                }
            }

            $archivo = TareaArchivo::create([
                'nombre_archivo' => $filename,
                'nombre_real_archivo' => $originalFileName,
                'tarea_id' => $tarea->id,
                'user_id' => Auth::id(),
                'formato' => $formato_imagen,
            ]);
            return response($archivo, 201);
        }
        return response('Archivo no legible', 422);
    }

    public function destroy(Tarea $tarea, TareaArchivo $documento){
        Storage::disk('local_tareas')->delete($tarea->id . '/' . $documento->nombre_archivo);
        $documento->delete();
        return response([], 200);
    }
}
