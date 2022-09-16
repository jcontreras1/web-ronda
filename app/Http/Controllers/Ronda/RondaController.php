<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class RondaController extends Controller
{
    public function index(){
        $circuitos = Circuito::all();
        $abiertas = Ronda::where('abierta', true)->get();
        $cerradas = Ronda::where('abierta', false)->get();
        return view('rondas.index')->with(compact([
            'abiertas',
            'cerradas',
            'circuitos',
        ]));
    }

    public function show(Ronda $ronda){
        return view('rondas.show')->with(compact([
            'ronda',
        ]));
    }

    public function store(Request $request){
        Ronda::create([
            'user_id' => Auth::user()->id,
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
