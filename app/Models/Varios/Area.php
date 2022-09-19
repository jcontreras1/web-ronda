<?php

namespace App\Models\Varios;

use App\Models\Ronda\Circuito;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = "area";
    protected $fillable = ['nombre'];

    public function circuitos(){
        return $this->hasMany(Circuito::class);
    }
}
