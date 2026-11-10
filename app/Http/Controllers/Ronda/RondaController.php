<?php

namespace App\Http\Controllers\Ronda;

use App\Http\Controllers\Controller;
use App\Models\Ronda\Checkpoint;
use App\Models\Ronda\Circuito;
use App\Models\Ronda\Ronda;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;



class RondaController extends Controller
{

    public function heatmap(){
        $checkpoints = Checkpoint::all();
        $circuitos = Circuito::all();
        return view('rondas.heatmap')->with(compact(['checkpoints', 'circuitos']));
    }


    public function index(Request $request){
        $request->validate([
            'q' => 'nullable|string|max:100',
            'sort' => 'nullable|in:created_at,creador',
            'direction' => 'nullable|in:asc,desc',
        ]);
        $search = trim($request->input('q') ?? '');
        $sort = $request->input('sort') ?? 'created_at';
        $direction = $request->input('direction') ?? 'desc';
        $circuitos_posibles = Circuito::whereIn('area_id', Auth::user()->areas()->pluck('area.id'))->get();
        $admin = evaluar_permisos(['ADM_SIS'], Auth::user()->tipos_usuario);
        $query = Ronda::query()
            ->with(['creador:id,nombre,apellido', 'circuito:id,titulo'])
            ->withCount(['checkpoints', 'novedades', 'images']);

        if (!$admin) {
            $query->whereIn('circuito_id', $circuitos_posibles->pluck('id'));
        }

        $abiertas = (clone $query)->where('abierta', true)->orderByDesc('id')->get();
        $cerradas = (clone $query)->where('abierta', false)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->whereHas('creador', function ($query) use ($search) {
                        $query->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido', 'like', "%{$search}%");
                    })->orWhereHas('circuito', fn ($query) => $query->where('titulo', 'like', "%{$search}%"));
                });
            });
        if ($sort === 'creador') {
            $cerradas->orderBy(User::withTrashed()->select('nombre')->whereColumn('users.id', 'ronda.user_id'), $direction);
        } else {
            $cerradas->orderBy('created_at', $direction);
        }
        $cerradas = $cerradas->orderByDesc('id')->paginate(25)->withQueryString();
        $cerradas->through(fn ($ronda) => $this->summary($ronda, $admin));

        // Áreas donde el usuario ya tiene una ronda abierta
        $areasConRondaAbierta = Ronda::query()
            ->join('circuito', 'ronda.circuito_id', '=', 'circuito.id')
            ->where('ronda.user_id', Auth::id())
            ->where('ronda.abierta', true)
            ->distinct()
            ->pluck('circuito.area_id')
            ->toArray();
        
        $circuitos_posibles = $circuitos_posibles->filter(function ($circuito) use ($areasConRondaAbierta) {
            return !in_array($circuito->area_id, $areasConRondaAbierta);});

        return Inertia::render('Rondas/Index', [
            'abiertas' => $abiertas->map(fn ($ronda) => $this->summary($ronda, $admin)),
            'cerradas' => $cerradas,
            'circuitos' => $circuitos_posibles->map->only(['id', 'titulo'])->values(),
            'filters' => ['q' => $search, 'sort' => $sort, 'direction' => $direction],
            'urls' => ['index' => route('ronda.index'), 'store' => route('ronda.store')],
        ]);
    }

    public function show(Ronda $ronda){
        $ronda->load(['circuito.geofences', 'checkpoints.user:id,nombre,apellido', 'checkpoints.images']);

        return Inertia::render('Rondas/Show', [
            'ronda' => [
                'id' => $ronda->id,
                'abierta' => (bool) $ronda->abierta,
                'circuito' => $ronda->circuito?->only(['id', 'titulo']),
                'geofences' => $ronda->circuito?->geofences->map->only(['id', 'latitud', 'longitud', 'radio']) ?? [],
                'checkpoints' => $ronda->checkpoints->map(fn ($checkpoint) => [
                    'id' => $checkpoint->id,
                    'latitud' => $checkpoint->latitud,
                    'longitud' => $checkpoint->longitud,
                    'novedad' => $checkpoint->novedad,
                    'created_at' => $checkpoint->created_at->toIso8601String(),
                    'usuario' => trim($checkpoint->user->nombre . ' ' . $checkpoint->user->apellido),
                    'images' => $checkpoint->images->map(fn ($image) => [
                        'id' => $image->id,
                        'url' => asset('storage/ronda/' . $checkpoint->id . '/' . $image->filename),
                    ]),
                ]),
            ],
            'map' => [
                'tiles' => variable_global('URL_TILES'),
                'token' => variable_global('API_TOKEN_MAPS'),
                'icons' => [
                    'ok' => asset('assets/img/markers/marker-ok.png'),
                    'warning' => asset('assets/img/markers/marker-warning.png'),
                    'photo' => asset('assets/img/markers/marker-photo.png'),
                ],
            ],
            'urls' => [
                'index' => route('ronda.index'),
                'checkpoint' => route('checkpoint.store', $ronda),
            ],
        ]);
    }

    private function summary(Ronda $ronda, bool $admin): array
    {
        return [
            'id' => $ronda->id,
            'creador' => trim($ronda->creador->nombre . ' ' . $ronda->creador->apellido),
            'circuito' => $ronda->circuito?->titulo,
            'created_at' => $ronda->created_at->toIso8601String(),
            'checkpoints_count' => $ronda->checkpoints_count,
            'novedades_count' => $ronda->novedades_count,
            'images_count' => $ronda->images_count,
            'canManage' => $admin || Auth::id() === $ronda->user_id,
            'urls' => [
                'show' => route('ronda.show', $ronda),
                'close' => route('ronda.cerrar', $ronda),
                'destroy' => route('ronda.destroy', $ronda),
            ],
        ];
    }

    public function store(Request $request){
        $request->validate(['circuito_id' => 'nullable|integer|exists:circuito,id']);

        $circuito = null;
        if($request->has('circuito_id'))
            $circuito = $request->circuito_id;
        Ronda::create([
            'user_id' => Auth::user()->id,
            'circuito_id' => $circuito,
        ]);
        return back()->with('success', 'Ronda creada');
    }

    public function destroy(Ronda $ronda){
        $checkpoints = $ronda->checkpoints;
        foreach($checkpoints as $checkpoint){
            foreach($checkpoint->images as $img){
                if(Storage::disk('public')->delete('ronda/' . $img->filename)) {
                    Storage::disk('public')->delete('ronda/' . $img->filename);
                }
                $img->delete();
            }
            $checkpoint->delete();
        }
        $ronda->delete();
        //toast('Ronda eliminada', 'success')->autoClose('1500');
        return back()->with('success', 'Ronda eliminada');
    }

    public function cerrar(Ronda $ronda){
        $ronda->update([
            'abierta' => false
        ]);
        return back()->with('success', 'Ronda cerrada');
    }

    public function definir(){
        return view('rondas.definir');
    }

    public function comparar(Ronda $ronda, Circuito $circuito){
        return view('rondas.comparar')->with(compact([
            'ronda',
            'circuito',
        ]));
    }
}
