<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Ronda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RondaController extends Controller
{
    public function index(){
        $rondas = Auth::user()->rondas()->where('abierta', true)->get();
        return view('rondas.index')->with(compact([
            'rondas',
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
}
