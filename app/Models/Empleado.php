<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empleado extends Model
{
    protected $table = 'empleados';
    
    protected $fillable = [
        'nombre',
        'apellido',
        'cargo',
        'domicilio',
        'ciudad',
        'provincia',
        'pais',
        'telefono',
        'email',
        'valor_hora',
        'cantidad_horas',
        'en_blanco',
        'observaciones'
    ];

    protected $casts = [
        'en_blanco' => 'boolean',
        'valor_hora' => 'decimal:2',
        'cantidad_horas' => 'decimal:2'
    ];

    public function liquidaciones(): HasMany
    {
        return $this->hasMany(Liquidacion::class);
    }
}
