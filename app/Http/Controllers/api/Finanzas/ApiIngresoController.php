<?php

namespace App\Http\Controllers\api\Finanzas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finanzas\IngresoResource;
use App\Models\Finanzas\Ingreso;
use Illuminate\Http\Request;

class ApiIngresoController extends Controller
{
    public function show(Ingreso $ingreso){
       return response(new IngresoResource($ingreso), 201);
   }
}
