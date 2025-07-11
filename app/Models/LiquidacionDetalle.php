<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidacionDetalle extends Model
{
    protected $table = 'liquidacion_detalles';

    protected $fillable = [
        'liquidacion_id',
        'fecha',
        'dia_semana',
        'estado',
        'horas_trabajadas',
        'horas_extra',
        'valor_hora',
        'valor_hora_extra',
        'premio_dia',
        'subtotal_normal',
        'subtotal_extra',
        'total_dia',
    ];

    protected $casts = [
        'fecha' => 'date',
        'horas_trabajadas' => 'decimal:2',
        'horas_extra' => 'decimal:2',
        'valor_hora' => 'decimal:2',
        'valor_hora_extra' => 'decimal:2',
        'premio_dia' => 'decimal:2',
        'subtotal_normal' => 'decimal:2',
        'subtotal_extra' => 'decimal:2',
        'total_dia' => 'decimal:2',
    ];

    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class);
    }
}
