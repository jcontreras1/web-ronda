<?php

namespace App\Models\Varios;

use App\Models\User;
use App\Models\Varios\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaUsuario extends Model
{
    use HasFactory;

    protected $table = "area_usuario";
    protected $fillable = [
        'user_id',
        'area_id',
        'es_jefe',
    ];

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }
    
}
