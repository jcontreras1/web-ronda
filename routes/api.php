<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\api\ApiUserController;
use App\Http\Controllers\api\Tarea\ApiTareaArchivoController;
use App\Http\Controllers\api\Tarea\ApiTareaComentarioController;
use App\Http\Controllers\api\Tarea\ApiTareaController;
use App\Http\Controllers\api\Tarea\ApiTareaSubtareaController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::name('api.')->group(function(){

		Route::resource('tarea', ApiTareaController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
		Route::prefix('tareas')->name('tarea.')->group(function(){
			Route::get('finalizadas', [ApiTareaController::class, 'finalizadas']);
			Route::post('reactivar', [ApiTareaController::class, 'reactivar']);
		});

		Route::resource('tarea/{tarea}/subtarea', ApiTareaSubtareaController::class)->only(['index', 'store', 'update', 'destroy']);
		Route::resource('tarea/{tarea}/comentario', ApiTareaComentarioController::class)->only(['index', 'store', 'update', 'destroy']);
		Route::resource('tarea/{tarea}/documento', ApiTareaArchivoController::class)->only(['index', 'store', 'destroy']);
	});
	Route::get('/users', function () {return User::limit(10)->get();});
	Route::post('/logout', [ApiAuthController::class, 'logout']);	
Route::post('/ping', [ApiAuthController::class, 'ping']);
});