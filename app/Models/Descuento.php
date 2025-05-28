<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    protected $table = 'descuentos';

    protected $fillable = [
        'nombre',
        'cantidad_minima',
        'descuento',
    ];

    /**
     * Obtener los productos asociados a este descuento.
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'productohasdescuento', 'descuento_id', 'producto_id');
    }
}
