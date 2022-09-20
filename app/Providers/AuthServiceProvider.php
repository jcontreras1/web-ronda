<?php

namespace App\Providers;

use App\Models\Empresa\Agencia;
use App\Models\Empresa\Rol;
use App\Models\Empresa\Salida;
use App\Models\Empresa\Voucher;
use App\Models\Finanzas\Ingreso;
use App\Models\Finanzas\Retiro;
use App\Models\Finanzas\Tarifa;
use App\Models\Finanzas\Venta;
use App\Models\Infraestructura\Embarcacion;
use App\Models\User;
use App\Models\Usuario\UsuarioProfesion;
use App\Models\Usuario\UsuarioTipoUsuario;
use App\Models\Varios\Color;
use App\Models\Varios\VariableGlobal;
use App\Policies\Empresa\AgenciaPolicy;
use App\Policies\Empresa\RolPolicy;
use App\Policies\Empresa\SalidaPolicy;
use App\Policies\Finanzas\IngresoPolicy;
use App\Policies\Finanzas\RetiroPolicy;
use App\Policies\Finanzas\TarifaPolicy;
use App\Policies\Finanzas\VentaPolicy;
use App\Policies\Finanzas\VoucherPolicy;
use App\Policies\Infraestructura\EmbarcacionPolicy;
use App\Policies\Usuario\UserPolicy;
use App\Policies\Usuario\UsuarioProfesionPolicy;
use App\Policies\Usuario\UsuarioTipoUsuarioPolicy;
use App\Policies\Varios\ColorPolicy;
use App\Policies\Varios\VariableGlobalPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Agencia::class => AgenciaPolicy::class,
        UsuarioProfesion::class => UsuarioProfesionPolicy::class,
        UsuarioTipoUsuario::class => UsuarioTipoUsuarioPolicy::class,
        Venta::class => VentaPolicy::class,
        Salida::class => SalidaPolicy::class,
        Rol::class => RolPolicy::class,
        Embarcacion::class => EmbarcacionPolicy::class,
        Ingreso::class => IngresoPolicy::class,
        Retiro::class => RetiroPolicy::class,
        Tarifa::class => TarifaPolicy::class,
        VariableGlobal::class => VariableGlobalPolicy::class,
        Color::class => ColorPolicy::class,
        Voucher::class => VoucherPolicy::class,
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        /*POSIBLES PERMISOS: ADM_SIS, USR_SIS, TRP_SIS*/
        Gate::define('administrar', function (User $user) {
            return evaluar_permisos(['ADM_SIS'], $user->tipos_usuario);
        });
        Gate::define('contable', function (User $user) {
            return evaluar_permisos(['ADM_SIS', 'CONTABLE'], $user->tipos_usuario);
        });        
        Gate::define('ventas', function (User $user) {
            return evaluar_permisos(['ADM_SIS', 'VENTAS'], $user->tipos_usuario);
        });        
        Gate::define('supervisar', function (User $user) {
            if(evaluar_permisos(['ADM_SIS'], $user->tipos_usuario)){
                return true;
            }else{
                foreach($user->areas as $area){
                    if($area->pivot->es_jefe){
                        /*Es jefe de algún área*/
                        return true;
                    }
                }
                return false;
            }
        });
    }
}
