<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Circuito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CircuitoController extends Controller
{
    public function index(){
        $circuitos = Circuito::all();
        return view('circuito.index')->with(compact(['circuitos']));
    }

    public function show(Circuito $circuito){
        return view('circuito.show')->with(compact([
            'circuito'
        ]));

    }
    public function update(Circuito $circuito, Request $request){
        $request->validate([
            'titulo' => 'required'
        ]);

        $circuito->update([
            'titulo' => $request->titulo,
        ]);
        toast('Circuito modificado', 'success')->autoClose(1500);
        return back();
    }

    public function store(Request $request){
        $circuito = Circuito::create([
            'created_by' => Auth::user()->id,
            'titulo' => 'Nombre temporal',
        ]);
        $circuito->update([
            'titulo' => 'Circuito ' . $circuito->id
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
