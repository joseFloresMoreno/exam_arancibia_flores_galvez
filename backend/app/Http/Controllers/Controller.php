<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "TodoCamisetas API",
    description: "API REST para la gestión de camisetas deportivas, clientes B2B, tallas y ventas. Permite administrar el stock de camisetas, gestionar clientes preferenciales y regulares, calcular precios con descuentos y registrar ventas."
)]
#[OA\Server(
    url: "http://localhost:8080/api",
    description: "Servidor de desarrollo local"
)]
#[OA\Tag(
    name: "Health",
    description: "Verificación del estado y disponibilidad de la API"
)]
#[OA\Tag(
    name: "Clientes",
    description: "Gestión de clientes B2B, categorías y descuentos"
)]
#[OA\Tag(
    name: "Camisetas",
    description: "Gestión del catálogo de camisetas deportivas y stock"
)]
#[OA\Tag(
    name: "Tallas",
    description: "Gestión de tallas disponibles para las camisetas"
)]
#[OA\Tag(
    name: "Ventas",
    description: "Gestión de ventas, detalle de productos y control de stock"
)]
abstract class Controller
{
    use ApiResponse;
}
