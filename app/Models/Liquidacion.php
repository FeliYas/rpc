<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'empleado_id',
        'fecha_inicio',
        'fecha_fin',
        'valor_hora_base',
        'total_horas_trabajadas',
        'total_horas_extra',
        'total_premios',
        'subtotal_base',
        'subtotal_extra',
        'total_neto',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'valor_hora_base' => 'decimal:2',
        'total_horas_trabajadas' => 'decimal:2',
        'total_horas_extra' => 'decimal:2',
        'total_premios' => 'decimal:2',
        'subtotal_base' => 'decimal:2',
        'subtotal_extra' => 'decimal:2',
        'total_neto' => 'decimal:2',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(LiquidacionDetalle::class);
    }
}
