<?php

namespace App\Models\Tarea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaTag extends Model
{
    use HasFactory;

    protected $table = 'tarea_tag';
    protected $fillable = [
        'descripcion',
    ];
}
