<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'fecha_venta'
    ];

    /**
     * Cliente al que pertenece la venta.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Detalles de la venta.
     */
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
