<?php

namespace Database\Seeders;
use App\Models\User;
use App\Models\Usuario\TipoUsuario;
use App\Models\Usuario\UsuarioTipoUsuario;
use App\Models\Varios\Area;
use App\Models\Varios\VariableGlobal;
use Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        /*Profesion: Fotografo, Guia, Traductor, Chofer*/
        /*TipoUsuario: ADM_SIS, USR_SIS*/

        $tipo_usr = TipoUsuario::where('nombre', 'USR_SIS')->first();

        if($tipo_usr){
            $usrs = UsuarioTipoUsuario::where('tipo_usuario_id', $tipo_usr->id)->get();
            foreach($usrs as $usr){
                $usr->delete();
            }
            $tipo_usr->delete();
        }

        TipoUsuario::updateOrCreate([
            'nombre' => 'ADM_SIS',
        ],[
            'descripcion' => 'Administrador General del Sistema. Permisos ilimitados.',
        ]);      
        TipoUsuario::firstOrcreate([
            'nombre' => 'USR_SIS',
        ],[
            'descripcion' => 'Usuario del sistema.',
        ]);        
        TipoUsuario::firstOrcreate([
            'nombre' => 'CON_SIS',
        ],[
            'descripcion' => 'Solo consultas',
        ]);
        
        $clave = Hash::make('admin1234');
        User::firstOrCreate(
            [
                'dni' => 123456789,
                'email' => 'admin@test.com',
            ],[
                'nombre' => 'admin',
                'apellido' => 'admin',
                'password' => $clave,
            ]);

        /*Me hago administrador del sistema*/
        UsuarioTipoUsuario::firstOrCreate([
            'usuario_id' => 1,
            'tipo_usuario_id' => 1
        ]);

 
        VariableGlobal::updateOrCreate([
            'clave' => 'AVATAR',
        ],[
            'valor' => '',
            'descripcion' => 'Nombre del archivo para la imagen de los membretes',
        ]);

        VariableGlobal::updateOrCreate([
            'clave' => 'API_TOKEN_MAPS',
        ],[
            'valor' => '',
            'descripcion' => 'Api key donde buscar las tejas',
        ]); 
        VariableGlobal::updateOrCreate([
            'clave' => 'URL_TILES',
        ],[
            'valor' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'descripcion' => 'Url donde buscar las tejas',
        ]);

        Area::create(['nombre' => 'CTAC MAS']);
        Area::create(['nombre' => 'CTAC MLPB']);
        Area::create(['nombre' => 'Auxiliares MLPB']);
        Area::create(['nombre' => 'Auxiliares MAS']);
        Area::create(['nombre' => 'Plazoleta Fiscal']);
   
    }
}
