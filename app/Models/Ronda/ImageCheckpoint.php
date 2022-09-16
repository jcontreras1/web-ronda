<?php

namespace App\Models\Ronda;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageCheckpoint extends Model
{
    use HasFactory;
    protected $table = 'imagen_checkpoint';

    protected $fillable = [
        'filename',
        'checkpoint_id',
    ];

}
