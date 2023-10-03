<?php

namespace App\Models\Tarea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaContieneTag extends Model
{
    use HasFactory;

    protected $table = 'tarea_contiene_tag';
    protected $fillable = [
        'tarea_id',
        'tarea_tag_id',
    ];
}
