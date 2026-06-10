<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Camiseta",
    title: "Camiseta",
    description: "Modelo completo de camiseta deportiva",
    required: [
        "titulo",
        "club",
        "pais",
        "tipo",
        "color",
        "precio",
        "stock",
        "codigo_producto"
    ]
)]
class CamisetaSchema
{
    #[OA\Property(property: "id", type: "integer", example: 1)]
    public int $id;

    #[OA\Property(
        property: "titulo",
        type: "string",
        example: "Camiseta Local 2025 - Selección Chilena"
    )]
    public string $titulo;

    #[OA\Property(
        property: "club",
        type: "string",
        example: "Selección Chilena"
    )]
    public string $club;

    #[OA\Property(
        property: "pais",
        type: "string",
        example: "Chile"
    )]
    public string $pais;

    #[OA\Property(
        property: "tipo",
        type: "string",
        example: "Local"
    )]
    public string $tipo;

    #[OA\Property(
        property: "color",
        type: "string",
        example: "Rojo"
    )]
    public string $color;

    #[OA\Property(
        property: "precio",
        type: "integer",
        example: 45000
    )]
    public int $precio;

    #[OA\Property(
        property: "precio_final",
        type: "integer",
        example: 40500,
        nullable: true,
        description: "Precio calculado según el descuento aplicado al cliente consultante"
    )]
    public ?int $precio_final;

    #[OA\Property(
        property: "stock",
        type: "integer",
        example: 20
    )]
    public int $stock;

    #[OA\Property(
        property: "detalles",
        type: "string",
        example: "Edición aniversario 2025",
        nullable: true
    )]
    public ?string $detalles;

    #[OA\Property(
        property: "codigo_producto",
        type: "string",
        example: "SCL2025L"
    )]
    public string $codigo_producto;

    #[OA\Property(
        property: "tallas",
        type: "array",
        items: new OA\Items(ref: "#/components/schemas/Talla"),
        nullable: true
    )]
    public ?array $tallas;

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
    schema: "CamisetaInput",
    title: "Camiseta Input",
    description: "Datos para crear o actualizar una camiseta",
    required: [
        "titulo",
        "club",
        "pais",
        "tipo",
        "color",
        "precio",
        "stock",
        "codigo_producto"
    ]
)]
class CamisetaInputSchema
{
    #[OA\Property(
        property: "titulo",
        type: "string",
        example: "Camiseta Local 2025 - Selección Chilena"
    )]
    public string $titulo;

    #[OA\Property(
        property: "club",
        type: "string",
        example: "Selección Chilena"
    )]
    public string $club;

    #[OA\Property(
        property: "pais",
        type: "string",
        example: "Chile"
    )]
    public string $pais;

    #[OA\Property(
        property: "tipo",
        type: "string",
        example: "Local"
    )]
    public string $tipo;

    #[OA\Property(
        property: "color",
        type: "string",
        example: "Rojo"
    )]
    public string $color;

    #[OA\Property(
        property: "precio",
        type: "integer",
        example: 45000
    )]
    public int $precio;

    #[OA\Property(
        property: "stock",
        type: "integer",
        example: 20
    )]
    public int $stock;

    #[OA\Property(
        property: "detalles",
        type: "string",
        example: "Edición aniversario 2025",
        nullable: true
    )]
    public ?string $detalles;

    #[OA\Property(
        property: "codigo_producto",
        type: "string",
        example: "SCL2025L"
    )]
    public string $codigo_producto;

    #[OA\Property(
        property: "tallas",
        type: "array",
        items: new OA\Items(type: "integer"),
        example: [1, 2, 3],
        nullable: true
    )]
    public ?array $tallas;
}
