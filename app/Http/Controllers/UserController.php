<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usuario\TipoUsuario;
use App\Models\Usuario\UsuarioTipoUsuario;
use App\Models\Varios\Area;
use App\Models\Varios\AreaUsuario;
use App\Notifications\BienvenidoUsuarioNuevo;
use Carbon\Carbon;
use DB;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(){
        $this->authorize('viewAny', User::class);
        
        $areas = Auth::user()->areas()->wherePivot('es_jefe', true)->get();
        $ids_usuario = [];
        foreach($areas as $area){
            foreach($area->usuarios as $usr){
                if(!in_array($usr->id, $ids_usuario) && $usr->id !== 1 && $usr->id !== Auth::user()->id){
                    $ids_usuario[] = $usr->id;
                }
            }
        } 

        $users = User::whereIn('id', $ids_usuario)->get();
        if(evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario)){
            $users = User::where('id', '<>', 1)->get();
        }
        return view('user.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('create', User::class);
        $tipos_usuario = TipoUsuario::whereNotIn('nombre', ['ADM_SIS', 'CON_SIS'])->get();
        $areas = Auth::user()->areas()->wherePivot('es_jefe', true)->get();
        if(evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario)){
            $tipos_usuario = TipoUsuario::all();
            $areas = Area::all();
        }
        return view('user.create',compact([
            'tipos_usuario',
            'areas',
        ]));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        $this->validate($request, [
            'nombre' => 'required',
            'email' => 'required|email',
            'rol' => 'required',
            'area' => 'required',
        ]);
        
        // $clave = Hash::make(Str::random(15));
        /*Sistemas Offline*/
        $clave = Hash::make('123456');
        $current_date_time = Carbon::now()->toDateTimeString(); 
        try {
            DB::beginTransaction();
            $user = User::withTrashed()->updateOrCreate([
                'email' => $request->email,
            ],[
                'nombre' => $request->nombre,
                'apellido' => $request->apellido, 
                'dni' => $request->dni,
                'fecha_nacimiento' => $request->fecha_nacimiento, 
                'telefono' => $request->telefono, 
                'password' => $clave, 
                'email_verified_at' => $current_date_time,
                'deleted_at' => null,
            ]);
            $token = Password::getRepository()->create($user);
            /*Asigno el tipo de usuario (ADM o USR)*/
            UsuarioTipoUsuario::firstOrCreate([
                'usuario_id' => $user->id,
                'tipo_usuario_id' => $request->rol
            ]);

            foreach($request->area as $area){
                AreaUsuario::create([
                    'area_id' => $area,
                    'user_id' => $user->id
                ]);
            }

            $user->notify(new BienvenidoUsuarioNuevo($token));
            DB::commit();
        } catch (\PDOException $e) {
            DB::rollBack();
            toast($e->getMessage(),'error');
            return back();
        }

        toast('Usuario registrado','success')->autoClose(2000);
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('user.show')->with(compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        $tipos_usuario = TipoUsuario::all();
        $areas = Auth::user()->areas()->wherePivot('es_jefe', true)->get();
        if(evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario)){
            $areas = Area::all();
        }
        return view('user.edit',compact('user'), compact([
            'tipos_usuario',
            'areas',
        ]));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $this->validate($request, [
            'nombre' => 'required',
            'email' => 'required|email'
        ]);

        $user->update($request->all());
        toast('Usuario actualizado','success')->autoClose(2000);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $curr_id = Auth::id();
        if($user->id == $curr_id){
            toast('No puede eliminar su propio Usuario','error')->autoClose(5000);
            return back();
        } 

        $user->delete();
        toast('Usuario eliminado','success')->autoClose(2000);
        return redirect()->route('user.index');       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = Auth::user();
        $this->authorize('profile', $user);
        $roles = TipoUsuario::all();
        return view('user.profile.perfil', compact(['user', 'roles']));
    }

    public function profile_update(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required',
            'email' => 'required|email'
        ]);
        $user = Auth::user();
        $this->authorize('profile', $user);
        $user->update($request->all());
        toast('Perfil actualizado','success')->autoClose(2000);
        return back();
    }

    public function password_update(Request $request)
    {   
        $user = Auth::user();
        $this->authorize('profile', $user);
        $this->validate($request, [
            'password' => 'required|min:5|confirmed',
            'password_confirmation' => 'required|min:5|same:password'
        ]);

        $pass = Hash::make($request->password);
        $user->update(['password' => $pass]);
        toast('Contraseña actualizada','success')->autoClose(2000);
        return back();
    }
}
