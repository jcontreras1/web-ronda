<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Checkpoint;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class RondaController extends Controller
{

    public function heatmap(){
        $checkpoints = Checkpoint::all();
        $circuitos = Circuito::all();
        return view('rondas.heatmap')->with(compact(['checkpoints', 'circuitos']));
    }


    public function index(){

        /*Áreas a las que pertenezco*/
        $areas = Auth::user()->areas;

        /*Circuitos de las áreas a las que pertenezco*/
        $circuitos_posibles = [];
        foreach($areas as $area){
            foreach($area->circuitos as $circuito){
                if(!in_array($circuito, $circuitos_posibles)){
                    $circuitos_posibles[] = $circuito;
                }
            }
        }

        /*Si puede administrar el sistema, verá esto. Caso contrario, se ve solo lo del área*/
        $abiertas = Ronda::where('abierta', true)->get();
        $cerradas = Ronda::where('abierta', false)
        ->with('creador')
        ->with('circuito')
        ->with('checkpoints')
        ->with('novedades')
        ->with('images')
        ->take(1000)
        ->orderBy('id', 'desc')->get();

        if(!evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario)){
            return "hola";
            /*Rondas cerradas cuyo circuito, pertenece a algún área a la que pertenezco*/
            $cerradas = Ronda::where('abierta', false)->whereIn('circuito_id', array_map(function($elem){return $elem['id'];}, $circuitos_posibles))->get();
            /*Rondas abiertas cuyo circuito, pertenece a algín área a la que pertenezco*/
            $abiertas = Ronda::where('abierta', true)->whereIn('circuito_id', array_map(function($elem){return $elem['id'];}, $circuitos_posibles))->get();
        }

        return view('rondas.index')->with(compact([
            'abiertas',
            'cerradas',
            // 'circuitos',
            'circuitos_posibles',
            'areas',
        ]));
    }

    public function show(Ronda $ronda){
        return view('rondas.show')->with(compact([
            'ronda',
        ]));
    }

    public function store(Request $request){

        $circuito = null;
        if($request->has('circuito_id'))
            $circuito = $request->circuito_id;
        Ronda::create([
            'user_id' => Auth::user()->id,
            'circuito_id' => $circuito,
        ]);
        toast('Ronda creada', 'success')->autoClose(1500);
        return back();
    }

    public function destroy(Ronda $ronda){
        $checkpoints = $ronda->checkpoints;
        foreach($checkpoints as $checkpoint){
            foreach($checkpoint->images as $img){
                if(Storage::disk('public')->delete('ronda/' . $img->filename)) {
                    Storage::disk('public')->delete('ronda/' . $img->filename);
                }
                $img->delete();
            }
            $checkpoint->delete();
        }
        $ronda->delete();
        toast('Ronda eliminada', 'success')->autoClose('1500');
        return back();
    }

    public function cerrar(Ronda $ronda){
        $ronda->update([
            'abierta' => false
        ]);
        toast('Ronda cerrada', 'success')->autoClose('1500');
        return back();
    }

    public function definir(){
        return view('rondas.definir');
    }

    public function comparar(Ronda $ronda, Circuito $circuito){
        return view('rondas.comparar')->with(compact([
            'ronda',
            'circuito',
        ]));
    }
}
