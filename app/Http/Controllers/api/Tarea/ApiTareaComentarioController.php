<?php

namespace App\Http\Controllers\api\Tarea;

use App\Models\Tarea\Tarea;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Tarea\TareaComentario;
use App\Http\Resources\Tarea\ComentarioResource;
use App\Http\Requests\Tarea\ComentarioCreateRequest;

class ApiTareaComentarioController extends Controller
{
    public function index(Tarea $tarea){
        return response(ComentarioResource::collection($tarea->comentarios()->orderBy('fijado', 'DESC')->orderBy('created_at', 'ASC')->get()), 200);
    }
    
    public function store(Tarea $tarea, ComentarioCreateRequest $request){
        $comentario = TareaComentario::create([
            'comentario' => $request['comentario'],
            'tarea_id' => $tarea->id,
            'user_id' => Auth::user()->id,
        ]);
        
        return response($comentario, 201);
    }
    
    public function update(Tarea $tarea, TareaComentario $comentario, Request $request){
        if($request->has('fijado') && $request->fijado == 1){
            /* Debo recorrer si ya había un fijado */
            $comentario_fijado = $tarea->comentarios->where('fijado', true)->first();
            if($comentario_fijado){
                $comentario_fijado->update(['fijado' => false]);
            }
        }
        $comentario->update($request->all());
        return response($comentario, 201);
    }
    
    public function destroy(Tarea $tarea, TareaComentario $comentario){
        $comentario->delete();
        return response([], 200);
    }
    
}
