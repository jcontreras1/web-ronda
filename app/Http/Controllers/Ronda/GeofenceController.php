<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\Geofence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeofenceController extends Controller
{

    public function store(Request $request, Circuito $circuito){
        $request->validate([
            'latitud' => 'required',
            'longitud' => 'required',
            'radio' => 'required',
        ]);

        Geofence::create([
            'circuito_id' => $circuito->id,
            'created_by' => Auth::user()->id,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'radio' => $request->radio,
        ]);

        toast('Punto de control agregado', 'success')->autoClose(1400);
        return redirect()->route('circuito.show', $circuito);
    }

    public function destroy(Circuito $circuito, Geofence $geofence){
        $geofence->delete();
        toast('Punto de control eliminado', 'success')->autoClose(1400);
        return redirect()->route('circuito.show', $circuito);
    }
}
