<?php

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\api\ApiUserController;
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
});