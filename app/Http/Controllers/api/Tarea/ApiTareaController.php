<?php

namespace App\Http\Controllers\api\Tarea;

use App\Models\Tarea\Tarea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea\TareaParticipante;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Tarea\TareaRequest;
use App\Http\Resources\Tarea\TareaResource;

class ApiTareaController extends Controller
{
    public function index(Request $request){
        $responsables = $request->responsables;
        $tareas = null;


        // if(evaluar_permisos(['ADM_TAREAS'], Auth::user()->roles)){
        //     $tareas = TareaResource::collection(
        //         Tarea::where('finalizada', false)
        //         ->when($responsables, function($query, $responsables){
        //             $query->whereIn('responsable_id', $responsables);
        //         })
        //         ->orderBy('updated_at', 'DESC')
        //         ->get()
        //     );
        // }else{
            $tareas = TareaResource::collection(
                Auth::user()
                ->tareas()
                ->where('finalizada', false)
                ->orderBy('updated_at', 'DESC')
                ->get()
            );
        // }
        return response($tareas, 200);
    }
    
    public function finalizadas(){
        $tareas = TareaResource::collection(Tarea::where('finalizada', true)->orderBy('updated_at', 'DESC')->get());
        return response($tareas, 200);
    }
    
    public function show(Tarea $tarea){
        $response = [
            'tarea' => $tarea,
            'subtareas' => $tarea->subtareas
        ];
        return response($response, 200);
    }
    
    public function store(TareaRequest $request){
        $creador = Auth::user();
        $tarea = Tarea::create([
            'titulo' => $request['titulo'],
            'creador_id' => $creador->id,
        ]);
        
        $tarea_participante = TareaParticipante::create([
            'user_id' => Auth::id(),
            'tarea_id' => $tarea->id,
        ]);

        return response($tarea, 201);
    }
    
    public function update(TareaRequest $request, Tarea $tarea){
        if($request->has('responsable_id') && $request->responsable_id !== null){

            $tarea_participante = TareaParticipante::firstOrCreate([
                'user_id' => $request->responsable_id,
                'tarea_id' => $tarea->id,
            ]);
        }
        $tarea->update($request->all());
        return response(new TareaResource($tarea), 201);
    }
    
    public function destroy(Tarea $tarea){
        /* Eliminar los comentarios */
        foreach($tarea->comentarios as $comentario){
            $comentario->delete();
        }
        /* Eliminar las subtareas */
        foreach($tarea->subtareas as $subtarea){
            $subtarea->delete();
        }        
        /* Eliminar los archivos */
        foreach($tarea->archivos as $documento){
            Storage::disk('local_tareas')->delete($tarea->id . '/' . $documento->nombre_archivo);
            $documento->delete();
        }
        /* Elimina todos los participantes de la tarea */
        foreach($tarea->tarea_participante as $participante){
            $participante->delete();
        }
        $tarea->delete();
        return response([], 200);
    }
    
    public function reactivar(Request $request){
        $tareas = Tarea::where('finalizada', true)->where('renovable', true)->get();
        foreach($tareas as $tarea){
            foreach($tarea->subtareas as $subtarea){
                $subtarea->update(['finalizada' => false]);
            }
            $tarea->update(['finalizada' => false]);
        }
        return response([], 201);
    }
}
