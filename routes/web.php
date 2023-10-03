<?php
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Empresa\ConfigController;
use App\Http\Controllers\Ronda\CheckpointController;
use App\Http\Controllers\Ronda\CircuitoController;
use App\Http\Controllers\Ronda\ExportController;
use App\Http\Controllers\Ronda\GeofenceController;
use App\Http\Controllers\Ronda\RondaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Usuario\AreaController;
use App\Http\Controllers\Usuario\AreaUsuarioController;
use App\Http\Controllers\Usuario\TipoUsuarioController;
use App\Http\Controllers\Varios\ColorController;
use App\Http\Controllers\testcontroller;
use App\Models\Ronda\Circuito;
use Illuminate\Support\Facades\Route;


Route::get('/', function () { return view('welcome'); });


Route::get('/tareas', [testcontroller::class, 'test']);

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
    Route::resource('area', AreaController::class);
    Route::resource('/user/{user}/area_usuario', AreaUsuarioController::class);
    Route::resource('ronda', RondaController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::patch('ronda/{ronda}/cerrar', [RondaController::class, 'cerrar'])->name('ronda.cerrar');
    Route::resource('ronda/{ronda}/checkpoint', CheckpointController::class)->only(['store', 'update', 'destroy']);
    Route::resource('circuito', CircuitoController::class)->only(['index', 'show', 'update', 'store', 'destroy']);
    Route::resource('circuito/{circuito}/geofence', GeofenceController::class)->only(['store', 'update', 'destroy']);
    /*Export*/
    Route::get('/export', [ExportController::class, 'index'])->name('export.index');    
    Route::get('/export/{ronda}', [ExportController::class, 'show'])->name('export.show');
    // Route::view('/tareas', 'tareas.index');
  
});
