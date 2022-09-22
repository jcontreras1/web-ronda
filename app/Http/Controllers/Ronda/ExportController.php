<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
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
        $cerradas = Ronda::where('abierta', false)->get();

        if(!evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario)){
            /*Rondas cerradas cuyo circuito, pertenece a algún área a la que pertenezco*/
            $cerradas = Ronda::where('abierta', false)->whereIn('circuito_id', array_map(function($elem){return $elem['id'];}, $circuitos_posibles))->get();
            /*Rondas abiertas cuyo circuito, pertenece a algín área a la que pertenezco*/
            $abiertas = Ronda::where('abierta', true)->whereIn('circuito_id', array_map(function($elem){return $elem['id'];}, $circuitos_posibles))->get();
        }

        return view('exports.index')->with(compact([
            'abiertas',
            'cerradas',
            // 'circuitos',
            'circuitos_posibles',
            'areas',
        ]));
    }

    public function show(Ronda $ronda){
     return view('exports.show')->with(compact([
        'ronda',
    ]));
 }
}
