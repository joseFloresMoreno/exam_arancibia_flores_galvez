<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Camiseta;
use App\Models\DetalleVenta;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Ventas",
    description: "Gestión de ventas de camisetas"
)]
class VentaController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: "/ventas",
        operationId: "listarVentas",
        tags: ["Ventas"],
        summary: "Listar ventas",
        description: "Obtiene todas las ventas registradas junto con sus clientes y detalles."
    )]
    #[OA\Response(
        response: 200,
        description: "Listado de ventas",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    type: "array",
                    items: new OA\Items(
                        ref: "#/components/schemas/Venta"
                    )
                )
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return $this->successResponse(
            Venta::with([
                'cliente',
                'detalles.camiseta'
            ])->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: "/ventas",
        operationId: "crearVenta",
        tags: ["Ventas"],
        summary: "Registrar venta",
        description: "Registra una venta junto con sus detalles. El sistema valida stock disponible y descuenta automáticamente las unidades vendidas.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/VentaInput"
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Venta registrada correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Venta"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos o stock insuficiente"
    )]
    #[OA\Response(
        response: 500,
        description: "Error interno al registrar la venta"
    )]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date',

            'detalles' => 'required|array|min:1',

            'detalles.*.camiseta_id' => 'required|exists:camisetas,id',
            'detalles.*.cantidad' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        DB::beginTransaction();

        try {

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'fecha_venta' => $request->fecha_venta
            ]);

            $cliente = $venta->cliente;

            foreach ($request->detalles as $detalle) {

                $camiseta = Camiseta::find($detalle['camiseta_id']);

                if ($camiseta->stock < $detalle['cantidad']) {

                    DB::rollBack();

                    return $this->errorResponse(
                        "Stock insuficiente para la camiseta {$camiseta->titulo}.",
                        422
                    );
                }

                $precioFinal = $camiseta->precio;

                if ($cliente->porcentaje_oferta > 0) {

                    $precioFinal = $camiseta->precio -
                        ($camiseta->precio * $cliente->porcentaje_oferta / 100);
                }

                $subtotal = $precioFinal * $detalle['cantidad'];

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'camiseta_id' => $camiseta->id,
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => round($precioFinal),
                    'subtotal' => round($subtotal)
                ]);

                $camiseta->decrement(
                    'stock',
                    $detalle['cantidad']
                );
            }

            DB::commit();

            return $this->successResponse(
                $venta->load([
                    'cliente',
                    'detalles.camiseta'
                ]),
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorResponse(
                'Error al registrar la venta.',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: "/ventas/{venta}",
        operationId: "obtenerVenta",
        tags: ["Ventas"],
        summary: "Obtener venta",
        description: "Obtiene una venta específica junto con su cliente y detalle de productos."
    )]
    #[OA\Parameter(
        name: "venta",
        in: "path",
        required: true,
        description: "ID de la venta",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Venta encontrada",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Venta"
                )
            ]
        )
    )]
    public function show(Venta $venta): JsonResponse
    {
        return $this->successResponse(
            $venta->load([
                'cliente',
                'detalles.camiseta'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: "/ventas/{venta}",
        operationId: "actualizarVenta",
        tags: ["Ventas"],
        summary: "Actualizar venta",
        description: "Actualiza los datos generales de una venta."
    )]
    #[OA\Parameter(
        name: "venta",
        in: "path",
        required: true,
        description: "ID de la venta",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "cliente_id",
                    type: "integer",
                    example: 1
                ),
                new OA\Property(
                    property: "fecha_venta",
                    type: "string",
                    format: "date",
                    example: "2026-06-10"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: "Venta actualizada correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Venta"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos"
    )]
    public function update(Request $request, Venta $venta): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:clientes,id',
            'fecha_venta' => 'required|date'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        $venta->update(
            $validator->validated()
        );

        return $this->successResponse($venta);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: "/ventas/{venta}",
        operationId: "eliminarVenta",
        tags: ["Ventas"],
        summary: "Eliminar venta",
        description: "Elimina una venta siempre que no posea detalles asociados."
    )]
    #[OA\Parameter(
        name: "venta",
        in: "path",
        required: true,
        description: "ID de la venta",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Venta eliminada correctamente"
    )]
    public function destroy(Venta $venta): JsonResponse
    {
        $venta->delete();

        return $this->successResponse([
            'mensaje' => 'Venta eliminada correctamente.'
        ]);
    }
}
