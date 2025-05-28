<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'orden',
        'path',
        'codigo',
        'titulo',
        'descripcion',
        'precio',
        'unidad',
        'categoria_id',
        'ficha',
        'adword'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function caracteristicas()
    {
        return $this->hasMany(Caracteristica::class);
    }

    public function imagenes()
    {
        return $this->hasMany(Productosimg::class)->orderBy('orden', 'asc');
    }

    public function imagenPrincipal()
    {
        return $this->hasMany(Productosimg::class)->orderBy('orden', 'asc')->limit(1);
    }

    /**
     * Obtener los descuentos asociados a este producto.
     */
    public function descuentos()
    {
        return $this->belongsToMany(Descuento::class, 'productohasdescuento', 'producto_id', 'descuento_id');
    }
}
