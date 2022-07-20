<?php

namespace App\Http\Controllers\Usuario;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Usuario\UsuarioTipoUsuario;
use Illuminate\Http\Request;

class TipoUsuarioController extends Controller
{
    public function store(User $user, Request $request)
    {
        $this->authorize('create', UsuarioTipoUsuario::class);
        if(!$request->exists('cargo')){
            toast('Sin cambios', 'success')->autoClose(2000);
            return back();
        }
        UsuarioTipoUsuario::updateOrCreate([
            'usuario_id' => $user->id,
        ],[
            'tipo_usuario_id' => $request->cargo
        ]);
        
        toast('Usuario actualizado', 'success')->autoClose(2000);
        return back();        
    }

    public function destroy(User $user, UsuarioTipoUsuario $cargo){
        $this->authorize('delete', $cargo);
        $cargo->delete();
        toast('Cargo eliminado', 'success')->autoClose(2000);
        return back();
    }
}
