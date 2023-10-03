<?php

namespace App\Models\Tarea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaArchivo extends Model
{
    use HasFactory;

    protected $table = 'tarea_archivo';
    protected $fillable = [
        'nombre_archivo',
        'nombre_real_archivo',
        'tarea_id',
        'subido_por',
        'user_id',
        'formato',
    ];
}
