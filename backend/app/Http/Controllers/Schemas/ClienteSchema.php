<?php

namespace App\Http\Controllers\Schemas;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Cliente",
    title: "Cliente",
    description: "Modelo completo de cliente B2B",
    required: [
        "nombre_comercial",
        "rut_comercial",
        "direccion",
        "categoria",
        "contacto_nombre",
        "contacto_email",
        "porcentaje_oferta"
    ]
)]
class ClienteSchema
{
    #[OA\Property(property: "id", type: "integer", example: 1)]
    public int $id;

    #[OA\Property(
        property: "nombre_comercial",
        type: "string",
        example: "90minutos"
    )]
    public string $nombre_comercial;

    #[OA\Property(
        property: "rut_comercial",
        type: "string",
        example: "76123456-7"
    )]
    public string $rut_comercial;

    #[OA\Property(
        property: "direccion",
        type: "string",
        example: "Providencia, Santiago"
    )]
    public string $direccion;

    #[OA\Property(
        property: "categoria",
        type: "string",
        enum: ["Regular", "Preferencial"],
        example: "Preferencial"
    )]
    public string $categoria;

    #[OA\Property(
        property: "contacto_nombre",
        type: "string",
        example: "Juan Pérez"
    )]
    public string $contacto_nombre;

    #[OA\Property(
        property: "contacto_email",
        type: "string",
        format: "email",
        example: "juan@90minutos.cl"
    )]
    public string $contacto_email;

    #[OA\Property(
        property: "porcentaje_oferta",
        type: "integer",
        example: 10
    )]
    public int $porcentaje_oferta;

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
    schema: "ClienteInput",
    title: "Cliente Input",
    description: "Datos para crear o actualizar un cliente",
    required: [
        "nombre_comercial",
        "rut_comercial",
        "direccion",
        "categoria",
        "contacto_nombre",
        "contacto_email",
        "porcentaje_oferta"
    ]
)]
class ClienteInputSchema
{
    #[OA\Property(
        property: "nombre_comercial",
        type: "string",
        example: "90minutos"
    )]
    public string $nombre_comercial;

    #[OA\Property(
        property: "rut_comercial",
        type: "string",
        example: "76123456-7"
    )]
    public string $rut_comercial;

    #[OA\Property(
        property: "direccion",
        type: "string",
        example: "Providencia, Santiago"
    )]
    public string $direccion;

    #[OA\Property(
        property: "categoria",
        type: "string",
        enum: ["Regular", "Preferencial"],
        example: "Preferencial"
    )]
    public string $categoria;

    #[OA\Property(
        property: "contacto_nombre",
        type: "string",
        example: "Juan Pérez"
    )]
    public string $contacto_nombre;

    #[OA\Property(
        property: "contacto_email",
        type: "string",
        format: "email",
        example: "juan@90minutos.cl"
    )]
    public string $contacto_email;

    #[OA\Property(
        property: "porcentaje_oferta",
        type: "integer",
        example: 10
    )]
    public int $porcentaje_oferta;
}
