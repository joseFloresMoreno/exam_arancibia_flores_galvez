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

    #[OA\Property(property: "titulo", type: "string", example: "Camiseta Local 2025 - Selección Chilena")]
    public string $titulo;

    #[OA\Property(property: "club", type: "string", example: "Selección Chilena")]
    public string $club;

    #[OA\Property(property: "pais", type: "string", example: "Chile")]
    public string $pais;

    #[OA\Property(property: "tipo", type: "string", example: "Local")]
    public string $tipo;

    #[OA\Property(property: "color", type: "string", example: "Rojo")]
    public string $color;

    #[OA\Property(property: "precio", type: "integer", example: 45000)]
    public int $precio;

    #[OA\Property(
        property: "precio_oferta",
        type: "integer",
        example: 38000,
        nullable: true,
        description: "Precio de oferta propio de la camiseta. Si está definido, tiene precedencia sobre el descuento del cliente."
    )]
    public ?int $precio_oferta;

    #[OA\Property(property: "stock", type: "integer", example: 20)]
    public int $stock;

    #[OA\Property(property: "detalles", type: "string", example: "Edición aniversario 2025", nullable: true)]
    public ?string $detalles;

    #[OA\Property(property: "codigo_producto", type: "string", example: "SCL2025L")]
    public string $codigo_producto;

    #[OA\Property(
        property: "tallas",
        type: "array",
        items: new OA\Items(ref: "#/components/schemas/Talla"),
        nullable: true
    )]
    public ?array $tallas;

    #[OA\Property(property: "created_at", type: "string", format: "date-time")]
    public string $created_at;

    #[OA\Property(property: "updated_at", type: "string", format: "date-time")]
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
    #[OA\Property(property: "titulo", type: "string", example: "Camiseta Local 2025 - Selección Chilena")]
    public string $titulo;

    #[OA\Property(property: "club", type: "string", example: "Selección Chilena")]
    public string $club;

    #[OA\Property(property: "pais", type: "string", example: "Chile")]
    public string $pais;

    #[OA\Property(property: "tipo", type: "string", example: "Local")]
    public string $tipo;

    #[OA\Property(property: "color", type: "string", example: "Rojo")]
    public string $color;

    #[OA\Property(property: "precio", type: "integer", example: 45000)]
    public int $precio;

    #[OA\Property(
        property: "precio_oferta",
        type: "integer",
        example: 38000,
        nullable: true,
        description: "Precio de oferta propio de la camiseta. Opcional. Si se define, tiene precedencia sobre el porcentaje_oferta del cliente al consultar precio."
    )]
    public ?int $precio_oferta;

    #[OA\Property(property: "stock", type: "integer", example: 20)]
    public int $stock;

    #[OA\Property(property: "detalles", type: "string", example: "Edición aniversario 2025", nullable: true)]
    public ?string $detalles;

    #[OA\Property(property: "codigo_producto", type: "string", example: "SCL2025L")]
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

#[OA\Schema(
    schema: "PrecioResponse",
    title: "Precio Response",
    description: "Resultado del cálculo de precio final de una camiseta para un cliente específico"
)]
class PrecioResponseSchema
{
    #[OA\Property(property: "cliente", type: "string", example: "Distribuidora El Gol SpA")]
    public string $cliente;

    #[OA\Property(property: "camiseta", type: "string", example: "Camiseta Local 2025 - Selección Chilena")]
    public string $camiseta;

    #[OA\Property(property: "precio_base", type: "integer", example: 45000)]
    public int $precio_base;

    #[OA\Property(
        property: "precio_oferta",
        type: "integer",
        example: 38000,
        nullable: true,
        description: "Precio de oferta propio de la camiseta. Si está definido, es el precio final."
    )]
    public ?int $precio_oferta;

    #[OA\Property(
        property: "porcentaje_oferta",
        type: "number",
        format: "float",
        example: 10.0,
        description: "Descuento porcentual del cliente. Se aplica solo si la camiseta no tiene precio_oferta."
    )]
    public float $porcentaje_oferta;

    #[OA\Property(
        property: "precio_final",
        type: "integer",
        example: 38000,
        description: "Precio final a cobrar. Resulta de precio_oferta si existe, o de aplicar porcentaje_oferta al precio_base."
    )]
    public int $precio_final;
}
