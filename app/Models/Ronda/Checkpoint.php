<?php

namespace App\Models\Ronda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $table = "checkpoint";
    protected $fillable = [
        'latitud',
        'longitud',
        'ronda_id',
    ];
}
