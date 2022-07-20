<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Infraestructura\Embarcacion;
use App\Models\Varios\Pais;
use App\Models\Varios\VariableGlobal;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(){
        $this->authorize('viewAny', VariableGlobal::class);
        $embarcaciones = Embarcacion::all();
        $embarcacion_d = obj_variable_global('EMBARCACION_POR_DEFECTO');
        $paises = Pais::all();
        $pais_d = obj_variable_global('PAIS_POR_DEFECTO');
        $variables = VariableGlobal::all();
        $color_d = obj_variable_global('USAR_COLORES_SALIDA');
        return view('empresa.configuraciones.index')->with(compact([
            'variables',
            'paises',
            'pais_d',
            'color_d',
            'embarcaciones',
            'embarcacion_d',
        ]));
    }

    public function update(VariableGlobal $config, Request $request){
        $this->authorize('update', $config);
        $config->update($request->all());
        toast('Variable modificada', 'success')->autoClose(2000);
        return back();
    }

    public function set_avatar(Request $request){
        $obj = obj_variable_global('AVATAR');
        $this->authorize('update', $obj);
        if($request->hasFile('avatar')){
            $avatar = request()->file('avatar')->getClientOriginalName();
            $request->validate([
                'avatar' => 'mimes:jpg,jpeg,png|required|max:2000'
            ]);
            $path = request()->file('avatar')->storeAs('public', 'img/' . $avatar);
            $obj->update(['valor' => $avatar]);
            toast('Imagen establecida', 'success')->autoClose(2000);
            return back();
        }
    }
}
