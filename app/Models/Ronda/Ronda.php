<?php

namespace App\Models\Ronda;

use App\Models\Ronda\Checkpoint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ronda extends Model
{
    use HasFactory;

    protected $table = 'ronda';
    protected $fillable = [
        'abierta',
        'user_id',
    ];

    public function checkpoints(){
        return $this->hasMany(Checkpoint::class);
    }

    public function creador(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
