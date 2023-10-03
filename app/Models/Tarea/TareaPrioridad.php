<?php

namespace App\Models\Tarea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaPrioridad extends Model
{
    use HasFactory;

    protected $table = 'tarea_prioridad';
    protected $fillable = [
        'descripcion',
        'color',
    ];
}
