<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturaDetalle extends Model
{
    protected $table = 'factura_detalles';
    
    protected $fillable = [
        'factura_id',
        'gravado',
        'iva_porcentaje',
        'iva_monto',
        'subtotal'
    ];

    /**
     * Obtener la factura asociada
     */
    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }
}
