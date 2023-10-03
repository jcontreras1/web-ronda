<?php

namespace App\Models\Tarea;

use App\Models\User;
use App\Models\Tarea\TareaTag;
use App\Models\Tarea\TareaArchivo;
use App\Models\Tarea\TareaSubtarea;
use App\Models\Tarea\TareaPrioridad;
use App\Models\Tarea\TareaComentario;
use App\Models\Tarea\TareaParticipante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tarea extends Model
{
    use HasFactory;

    protected $table = 'tarea';
    protected $fillable = [
        'titulo',
        'descripcion',
        'creador_id',
        'responsable_id',
        'prioridad_id',
        'vencimiento',
        'renovable',
        'finalizada',
    ];

    public function subtareas(){
        return $this->hasMany(TareaSubtarea::class);
    }

    public function prioridad(){
        return $this->hasOne(TareaPrioridad::class);
    }

    public function tags(){
        return $this->belongsToMany(TareaTag::class, 'tarea_contiene_tag');
    }

    public function creador(){
        return $this->hasOne(User::class, 'id', 'creador_id');
    }

    public function responsable(){
        return $this->hasOne(User::class, 'id', 'responsable_id');
    }

    public function archivos(){
        return $this->hasMany(TareaArchivo::class);
    }

    public function comentarios(){
        return $this->hasMany(TareaComentario::class);
    }

    public function participantes(){
        return $this->belongsToMany(User::class, 'tarea_participante');
    }

    public function tarea_participante(){
        return $this->hasMany(TareaParticipante::class);
    }
}
