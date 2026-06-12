<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_comercial',
        'rut_comercial',
        'direccion',
        'categoria',
        'contacto_nombre',
        'contacto_email',
        'porcentaje_oferta',
    ];

    protected $casts = [
        'porcentaje_oferta' => 'float',
    ];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
