<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    /**
     * Relación muchos a muchos con camisetas.
     */
    public function camisetas()
    {
        return $this->belongsToMany(Camiseta::class);
    }
}
