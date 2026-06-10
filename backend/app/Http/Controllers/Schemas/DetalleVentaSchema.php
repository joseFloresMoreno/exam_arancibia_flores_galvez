<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "DetalleVenta",
    title: "Detalle de Venta",
    description: "Detalle asociado a una venta",
    required: [
        "venta_id",
        "camiseta_id",
        "cantidad",
        "precio_unitario",
        "subtotal"
    ]
)]
class DetalleVentaSchema
{
    #[OA\Property(property: "id", type: "integer", example: 1)]
    public int $id;

    #[OA\Property(property: "venta_id", type: "integer", example: 1)]
    public int $venta_id;

    #[OA\Property(property: "camiseta_id", type: "integer", example: 1)]
    public int $camiseta_id;

    #[OA\Property(property: "cantidad", type: "integer", example: 2)]
    public int $cantidad;

    #[OA\Property(property: "precio_unitario", type: "integer", example: 40500)]
    public int $precio_unitario;

    #[OA\Property(property: "subtotal", type: "integer", example: 81000)]
    public int $subtotal;

    #[OA\Property(
        property: "camiseta",
        ref: "#/components/schemas/Camiseta",
        nullable: true
    )]
    public mixed $camiseta;

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
    schema: "DetalleVentaInput",
    title: "Detalle de Venta Input",
    description: "Datos necesarios para agregar una camiseta a una venta",
    required: [
        "camiseta_id",
        "cantidad"
    ]
)]
class DetalleVentaInputSchema
{
    #[OA\Property(
        property: "camiseta_id",
        type: "integer",
        example: 1
    )]
    public int $camiseta_id;

    #[OA\Property(
        property: "cantidad",
        type: "integer",
        minimum: 1,
        example: 2
    )]
    public int $cantidad;
}
