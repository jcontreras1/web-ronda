<?php

namespace App\Http\Controllers\api\Tarea;

use App\Models\Tarea\Tarea;
use Illuminate\Http\Request;
use App\Models\Tarea\TareaSubtarea;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Tarea\SubtareaCreateRequest;

class ApiTareaSubtareaController extends Controller
{
    public function index(Tarea $tarea){
        return response($tarea->subtareas, 200);
    }

    public function store(Tarea $tarea, SubtareaCreateRequest $request){
        $subtarea = TareaSubtarea::create([
            'titulo' => $request['titulo'],
            'tarea_id' => $tarea->id,
            'creador_id' => Auth::user()->id,
        ]);
        return response($subtarea, 201);
    }

    public function update(Request $request, Tarea $tarea, TareaSubtarea $subtarea){
        $subtarea->update($request->all());
        return response($subtarea, 201);
    }

    public function destroy(Tarea $tarea, TareaSubtarea $subtarea){
        $subtarea->delete();
        return response([], 200);
    }
}
