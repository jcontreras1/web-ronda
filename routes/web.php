<?php

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Empresa\AgenciaController;
use App\Http\Controllers\Empresa\ConfigController;
use App\Http\Controllers\Empresa\RolController;
use App\Http\Controllers\Empresa\SalidaController;
use App\Http\Controllers\Empresa\VentaController;
use App\Http\Controllers\Finanzas\CajaController;
use App\Http\Controllers\Finanzas\IngresoController;
use App\Http\Controllers\Finanzas\RetiroController;
use App\Http\Controllers\Finanzas\TarifaController;
use App\Http\Controllers\Finanzas\VoucherController;
use App\Http\Controllers\Infraestructura\EmbarcacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Usuario\ProfesionController;
use App\Http\Controllers\Usuario\TipoUsuarioController;
use App\Http\Controllers\Varios\ColorController;
use Illuminate\Support\Facades\Route;

Route::get('/pepe', function(){
    abort(500);
});

Route::get('/', function () { return view('welcome'); });
Route::get('clave/definir/{token}', [ResetPasswordController::class, 'showDefineForm'])->name('password.define');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Auth::routes();
Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('/user/{user}/cargo', TipoUsuarioController::class)->only(['store', 'destroy']);
    Route::resource('/user/{user}/profesion', ProfesionController::class)->only(['store', 'destroy']);
    Route::resource('embarcacion', EmbarcacionController::class)->except(['create', 'show']);
    Route::resource('salida', SalidaController::class)->except(['show']);
    Route::get('salida/{salida}/pasajeros', [SalidaController::class, 'pasajeros'])->name('salida.pasajeros');
    Route::get('salidas', [SalidaController::class, 'salidas'])->name('salidas.index');
    Route::post('salidas', [SalidaController::class, 'bulk'])->name ('salidas.bulk');
    Route::resource('salida/{salida}/venta', VentaController::class)->only(['create', 'store']);
    Route::get('venta/{venta}/', [VentaController::class, 'show'])->name('venta.show');
    Route::get('ventas', [VentaController::class, 'index'])->name('venta.index');
    Route::resource('/agencia', AgenciaController::class, ['parameters' => ['agencia' => 'agencia']])->except('create');
    Route::resource('/rol', RolController::class)->except(['create', 'destroy']);
    Route::resource('/color', ColorController::class)->only(['index', 'store', 'destroy']);
    Route::get('/rol/{rol}/pdf', [RolController::class, 'pdf'])->name('rol.pdf');
    Route::resource('/tarifa', TarifaController::class)->except(['edit', 'create']);
    Route::resource('/voucher', VoucherController::class)->only(['update']);
    Route::resource('retiro', RetiroController::class)->only(['store', 'update', 'destroy']);
    Route::resource('ingreso', IngresoController::class)->only(['store', 'update', 'destroy']);

    Route::resource('/config', ConfigController::class)->only(['index', 'update']);
    Route::post('/config/avatar', [ConfigController::class, 'set_avatar'])->name('config.avatar');

    Route::prefix('user')->name('user.')->group(function(){
        Route::get('/profile/show', [UserController::class, 'profile'])->name('profile.show');
        Route::patch('/profile/update', [UserController::class, 'profile_update'])->name('profile.update');
        Route::patch('/profile/password/', [UserController::class, 'password_update'])->name('profile.password.update');
    });

    Route::prefix('donwload')->name('download.')->group(function(){
        Route::get('excel/caja/dia', [CajaController::class, 'excel_download'])->name('caja.dia.excel');
    });
    Route::get('/caja', [CajaController::class, 'del_dia'])->name('caja.dia');

});
