<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Empresa\Agencia;
use Illuminate\Http\Request;

class ApiAgenciaController extends Controller
{
    public function store(Request $request){
        if(!$request->has('razon_social') || $request->razon_social == ""){
            return response(
                [
                    'message' => 'La razón social, es obligatoria'
                ], 400);

        }
        $agencia = Agencia::withTrashed()->updateOrCreate([
            'razon_social' => $request->razon_social,
        ],[
            'email' => $request->email,
            'telefono' => $request->telefono,
            'cuit' => $request->cuit,
            'deleted_at' => null,
            'sync' => false,
        ]);

        return response($agencia, 201);
    }
}
