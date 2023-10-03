<?php

namespace App\Models\Tarea;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaParticipante extends Model
{
    use HasFactory;
    protected $table = 'tarea_participante';
    protected $fillable = ['user_id', 'tarea_id'];
}
