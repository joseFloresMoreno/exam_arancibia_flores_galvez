<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Talla",
    title: "Talla",
    description: "Modelo completo de talla disponible para camisetas",
    required: [
        "nombre"
    ]
)]
class TallaSchema
{
    #[OA\Property(
        property: "id",
        type: "integer",
        example: 1
    )]
    public int $id;

    #[OA\Property(
        property: "nombre",
        type: "string",
        example: "M"
    )]
    public string $nombre;

    #[OA\Property(
        property: "created_at",
        type: "string",
        format: "date-time"
    )]
    public string $created_at;

    #[OA\Property(
        property: "updated_at",
        type: "string",
        format: "date-time"
    )]
    public string $updated_at;
}

#[OA\Schema(
    schema: "TallaInput",
    title: "Talla Input",
    description: "Datos para crear o actualizar una talla",
    required: [
        "nombre"
    ]
)]
class TallaInputSchema
{
    #[OA\Property(
        property: "nombre",
        type: "string",
        example: "M"
    )]
    public string $nombre;
}
