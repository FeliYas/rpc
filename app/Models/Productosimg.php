<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productosimg extends Model
{
    // En tu modelo Productosimg
    protected $fillable = [
        'producto_id',
        'path',
        'orden'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
