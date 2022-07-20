<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ApiSincroController;
use App\Http\Controllers\api\ApiAgenciaController;
use App\Http\Controllers\api\ApiUserController;
use App\Http\Controllers\api\Finanzas\ApiIngresoController;
use App\Http\Controllers\api\Finanzas\ApiRetiroController;
use App\Http\Controllers\api\Finanzas\ApiVoucherController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::get('/users', function () {return User::limit(10)->get();});
	Route::post('/logout', [ApiAuthController::class, 'logout']);
	Route::post('/ping', [ApiAuthController::class, 'ping']);

	Route::name('api.')->group(function(){
		Route::resource('/agencia', ApiAgenciaController::class);
		Route::resource('/ingreso', ApiIngresoController::class)->only('show');
		Route::resource('/retiro', ApiRetiroController::class)->only('show');
		Route::resource('/voucher', ApiVoucherController::class)->only('show');
	});


	Route::post('/sincro/venta', [ApiSincroController::class, 'venta']);
	Route::post('/sincro/ingreso', [ApiSincroController::class, 'ingreso']);
	Route::post('/sincro/gasto', [ApiSincroController::class, 'gasto']);
	Route::post('/sincro/pasajero', [ApiSincroController::class, 'pasajero']);
	Route::post('/sincro/viaje', [ApiSincroController::class, 'viaje']);
	Route::post('/sincro/salida', [ApiSincroController::class, 'salida']);
	Route::post('/sincro/embarcacion', [ApiSincroController::class, 'embarcacion']);
	Route::post('/sincro/factura_pasajero', [ApiSincroController::class, 'factura_pasajero']);
	Route::post('/sincro/rol', [ApiSincroController::class, 'rol']);
	Route::post('/sincro/saldo_inicial', [ApiSincroController::class, 'saldo_inicial']);
	Route::post('/sincro/tarifa', [ApiSincroController::class, 'tarifa']);
	Route::get('/{user}/roles', [ApiUserController::class, 'roles'])->name('api.user.roles');


});