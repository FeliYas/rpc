<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'titulo',
        'destacado',
        'adword'
    ];
}
