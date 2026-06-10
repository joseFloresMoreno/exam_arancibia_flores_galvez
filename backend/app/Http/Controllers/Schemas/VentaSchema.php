<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Venta",
    title: "Venta",
    description: "Modelo completo de una venta realizada por un cliente",
    required: [
        "cliente_id",
        "fecha_venta"
    ]
)]
class VentaSchema
{
    #[OA\Property(
        property: "id",
        type: "integer",
        example: 1
    )]
    public int $id;

    #[OA\Property(
        property: "cliente_id",
        type: "integer",
        example: 1
    )]
    public int $cliente_id;

    #[OA\Property(
        property: "fecha_venta",
        type: "string",
        format: "date",
        example: "2026-06-10"
    )]
    public string $fecha_venta;

    #[OA\Property(
        property: "cliente",
        ref: "#/components/schemas/Cliente",
        nullable: true
    )]
    public mixed $cliente;

    #[OA\Property(
        property: "detalles",
        type: "array",
        items: new OA\Items(
            ref: "#/components/schemas/DetalleVenta"
        )
    )]
    public array $detalles;

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
    schema: "VentaInput",
    title: "Venta Input",
    description: "Datos necesarios para registrar una venta",
    required: [
        "cliente_id",
        "fecha_venta",
        "detalles"
    ]
)]
class VentaInputSchema
{
    #[OA\Property(
        property: "cliente_id",
        type: "integer",
        example: 1
    )]
    public int $cliente_id;

    #[OA\Property(
        property: "fecha_venta",
        type: "string",
        format: "date",
        example: "2026-06-10"
    )]
    public string $fecha_venta;

    #[OA\Property(
        property: "detalles",
        type: "array",
        items: new OA\Items(
            ref: "#/components/schemas/DetalleVentaInput"
        )
    )]
    public array $detalles;
}
