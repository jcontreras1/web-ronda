<?php

namespace App\Http\Controllers\api\Finanzas;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finanzas\RetiroResource;
use App\Models\Finanzas\Retiro;
use Illuminate\Http\Request;

class ApiRetiroController extends Controller
{
    public function show(Retiro $retiro){
        return response( new RetiroResource($retiro), 201);
   }
}
