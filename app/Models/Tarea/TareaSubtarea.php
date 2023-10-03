<?php

namespace App\Models\Tarea;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaSubtarea extends Model
{
    use HasFactory;

    protected $table = 'tarea_subtarea';
    protected $fillable = [
        'titulo',
        'tarea_id',
        'creador_id',
        'vencimiento',
        'finalizada',
    ];

    public function creador(){
        return $this->hasOne(User::class, 'creador_id');
    }
}
