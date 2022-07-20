<?php

namespace App\Models\Varios;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariableGlobal extends Model
{
    use HasFactory;

    protected $table = 'variable_global';
    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];
}
