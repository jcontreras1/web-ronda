<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Varios\VariableGlobal;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index(){
        $this->authorize('viewAny', VariableGlobal::class);
        $variables = VariableGlobal::all();
        return view('empresa.configuraciones.index')->with(compact([
            'variables',
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
