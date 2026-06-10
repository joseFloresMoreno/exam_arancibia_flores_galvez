<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    use HasFactory;

    protected $table = 'detalle_ventas';

    protected $fillable = [
        'venta_id',
        'camiseta_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    /**
     * Venta a la que pertenece el detalle.
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Camiseta vendida.
     */
    public function camiseta()
    {
        return $this->belongsTo(Camiseta::class);
    }
}
