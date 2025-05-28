<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    
    protected $table = 'pedidos';
    
    protected $fillable = [
        'cliente_id', 
        'productos', 
        'cantidad', 
        'completado',
        'entrega', 
        'mensaje', 
        'archivo'
    ];
    
    protected $casts = [
        'productos' => 'array',
        'completado' => 'boolean',
        'entrega' => 'boolean',
    ];
    
    /**
     * Obtener el cliente que hizo este pedido
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    /**
     * Método para obtener el número de pedido formateado
     */
    public function getNumeroPedidoAttribute()
    {
        return 'PED-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Método para obtener el estado textual del pedido
     */
    public function getEstadoAttribute()
    {
        return $this->completado ? 'Completado' : 'Pendiente';
    }
    
    /**
     * Método para obtener el tipo de entrega textual
     */
    public function getTipoEntregaAttribute()
    {
        return $this->entrega ? 'Transporte' : 'Retiro en fábrica';
    }
    
    /**
     * Método flexible para obtener los códigos de productos
     * Maneja múltiples formatos posibles del JSON
     */
    public function getCodigosProductosAttribute()
    {
        $productos = $this->productos;
        $codigos = [];
        
        if (empty($productos)) {
            return $codigos;
        }
        
        // Si productos es un string, intentar decodificarlo
        if (is_string($productos)) {
            $productos = json_decode($productos, true);
        }
        
        // Si productos no es un array después de intentar decodificarlo, retornar vacío
        if (!is_array($productos) && !is_object($productos)) {
            return $codigos;
        }
        
        foreach ($productos as $key => $producto) {
            // Caso 1: Producto es un array asociativo con clave 'codigo'
            if (is_array($producto) && isset($producto['codigo'])) {
                $codigos[] = $producto['codigo'];
            }
            // Caso 2: Producto es un objeto con propiedad 'codigo'
            elseif (is_object($producto) && isset($producto->codigo)) {
                $codigos[] = $producto->codigo;
            }
            // Caso 3: La clave misma es 'codigo'
            elseif (is_string($key) && $key === 'codigo') {
                $codigos[] = $producto;
            }
            // Caso 4: El producto mismo es un código directo (string)
            elseif (is_string($producto)) {
                $codigos[] = $producto;
            }
        }
        
        return $codigos;
    }
    
    /**
     * Método para depuración - Devuelve la estructura JSON de productos
     */
    public function getProductosDebugAttribute()
    {
        return json_encode($this->productos, JSON_PRETTY_PRINT);
    }
}