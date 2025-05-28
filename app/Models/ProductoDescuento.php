<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductoDescuento extends Pivot
{
    protected $table = 'productohasdescuento';

    protected $fillable = [
        'producto_id',
        'descuento_id',
    ];

    /**
     * Indica si el modelo debe ser marcado con timestamp.
     *
     * @var bool
     */
    public $timestamps = true;
    
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    public function descuento()
    {
        return $this->belongsTo(Descuento::class, 'descuento_id');
    }
}
