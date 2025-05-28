<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    protected $table = 'precios';

    protected $fillable = [
        'orden',
        'lista',
        'titulo',
    ];
}
