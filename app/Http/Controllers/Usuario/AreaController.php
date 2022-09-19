<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\Varios\Area;
use App\Models\Varios\AreaUsuario;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(){
        $areas = Area::all();
        return view('area.index')->with(compact('areas'));
    }

    public function store(Request $request){
        $request->validate([
            'nombre' => 'required'
        ]);
        Area::create([
            'nombre' => $request->nombre
        ]);
        toast('Area creada')->autoClose(1500);
        return back();
    }

    public function update(Area $area, Request $request){
        $request->validate([
            'nombre' => 'required'
        ]);

        $area->update([
            'nombre' => $request->nombre
        ]);
        toast('Area actualizada')->autoClose(1500);
        return back();
    }

    public function destroy(Area $area){
        $areas_usuario = AreaUsuario::where('area_id', $area->id)->get();
        foreach($areas_usuario as $area){
            $area->delete();
        }
        $area->delete();
        toast('Area Eliminada')->autoClose(1500);
        return back();
    }
}