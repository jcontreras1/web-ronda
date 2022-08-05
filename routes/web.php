<?php
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Empresa\ConfigController;
use App\Http\Controllers\Ronda\CheckpointController;
use App\Http\Controllers\Ronda\RondaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Usuario\TipoUsuarioController;
use App\Http\Controllers\Varios\ColorController;
use Illuminate\Support\Facades\Route;

Route::get('/pepe', function(){abort(500);});

Route::get('/', function () { return view('welcome'); });
Route::get('clave/definir/{token}', [ResetPasswordController::class, 'showDefineForm'])->name('password.define');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// Auth::routes();
Auth::routes(['register' => false]);

Route::middleware(['auth'])->group(function () {
    Route::resource('user', UserController::class)->except(['show']);
    Route::resource('/user/{user}/cargo', TipoUsuarioController::class)->only(['store', 'destroy']);

    Route::resource('/config', ConfigController::class)->only(['index', 'update']);
    Route::post('/config/avatar', [ConfigController::class, 'set_avatar'])->name('config.avatar');

    Route::prefix('user')->name('user.')->group(function(){
        Route::get('/profile/show', [UserController::class, 'profile'])->name('profile.show');
        Route::patch('/profile/update', [UserController::class, 'profile_update'])->name('profile.update');
        Route::patch('/profile/password/', [UserController::class, 'password_update'])->name('profile.password.update');
    });

    Route::resource('ronda', RondaController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::patch('ronda/{ronda}/cerrar', [RondaController::class, 'cerrar'])->name('ronda.cerrar');
    Route::resource('/{ronda}/checkpoint', CheckpointController::class)->only(['store', 'update', 'destroy']);


});
