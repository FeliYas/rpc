<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    protected $table = 'proveedores';
    
    protected $fillable = [
        'dni',
        'denominacion'
    ];

    /**
     * Obtener las facturas del proveedor
     */
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}
