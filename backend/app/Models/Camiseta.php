<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camiseta extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'club',
        'pais',
        'tipo',
        'color',
        'precio',
        'stock',
        'detalles',
        'codigo_producto'
    ];

    /**
     * Relación muchos a muchos con tallas.
     */
    public function tallas()
    {
        return $this->belongsToMany(Talla::class);
    }

    /**
     * Relación con detalle de ventas.
     */
    public function detallesVenta()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
