<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Circuito;
use App\Models\Varios\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircuitoController extends Controller
{
    public function index(){
        $circuitos = Circuito::all();
        $areas_mias = Auth::user()->areas;
        return view('circuito.index')->with(compact(['circuitos', 'areas_mias']));
    }

    public function show(Circuito $circuito){
        return view('circuito.show')->with(compact([
            'circuito'
        ]));

    }
    public function update(Circuito $circuito, Request $request){
        $request->validate([
            'titulo' => 'required',
        ]);

        $circuito->update([
            'titulo' => $request->titulo,
        ]);
        toast('Circuito modificado', 'success')->autoClose(1500);
        return back();
    }

    public function store(Request $request){
        $request->validate([
            'area_id' => 'required',
        ]);
        $circuito = Circuito::create([
            'created_by' => Auth::user()->id,
            'titulo' => 'Nombre temporal',
            'area_id' => $request->area_id,
        ]);

        $area = Area::find($request->area_id);

        $circuito->update([
            'titulo' => ucwords($area->nombre)
        ]);

        toast('Circuito agregado', 'success')->autoClose(1500);
        return back();
    }

    public function destroy(Circuito $circuito){
        $geofences = $circuito->geofences;
        foreach($geofences as $geofence){
            $geofence->delete();
        }
        $circuito->delete();
        toast('Circuito eliminado', 'success')->autoClose(1500);
        return back();
    }
}
