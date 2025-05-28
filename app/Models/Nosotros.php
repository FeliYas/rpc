<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nosotros extends Model
{
    protected $table = 'nosotros';

    protected $fillable = [
        'path',
        'titulo',
        'descripcion',
        'imagen1',
        'titulo1',
        'descripcion1',
        'imagen2',
        'titulo2',
        'descripcion2',
        'imagen3',
        'titulo3',
        'descripcion3'
    ];
}
