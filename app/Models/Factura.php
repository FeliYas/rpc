<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Factura extends Model
{
    protected $fillable = [
        'fecha',
        'tipo',
        'puntoventa',
        'nrofactura',
        'proveedor_id',
        'moneda',
        'tipo_cambio',
        'importe_total'
    ];

    /**
     * Obtener el proveedor asociado a la factura
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Obtener los detalles de la factura
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(FacturaDetalle::class);
    }
}
