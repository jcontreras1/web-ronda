<?php

namespace App\Models\Tarea;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TareaComentario extends Model
{
    use HasFactory;

    protected $table = 'tarea_comentario';
    protected $fillable = [
        'tarea_id',
        'user_id',
        'comentario',
        'fijado',
    ];

    public function creador(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
