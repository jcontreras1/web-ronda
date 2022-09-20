<?php

namespace App\Models\Ronda;

use App\Models\Ronda\ImageCheckpoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkpoint extends Model
{
    use HasFactory;

    protected $table = "checkpoint";
    protected $fillable = [
        'latitud',
        'longitud',
        'novedad',
        'ronda_id',
        'user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class)->withTrashed();
    }


    public function images(){
        return $this->hasMany(ImageCheckpoint::class);
    }
}
