<?php

namespace App\Http\Controllers;

use App\Models\Camiseta;
use App\Models\Cliente;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Camisetas",
    description: "Gestión de camisetas deportivas"
)]
class CamisetaController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: "/camisetas",
        operationId: "listarCamisetas",
        tags: ["Camisetas"],
        summary: "Listar camisetas",
        description: "Obtiene todas las camisetas registradas junto con sus tallas disponibles."
    )]
    #[OA\Response(
        response: 200,
        description: "Listado de camisetas",
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
                        ref: "#/components/schemas/Camiseta"
                    )
                )
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return $this->successResponse(
            Camiseta::with('tallas')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: "/camisetas",
        operationId: "crearCamiseta",
        tags: ["Camisetas"],
        summary: "Crear camiseta",
        description: "Registra una nueva camiseta en el catálogo.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/CamisetaInput"
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Camiseta creada correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Camiseta"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos"
    )]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo'          => 'required|string|max:255',
            'club'            => 'required|string|max:255',
            'pais'            => 'required|string|max:100',
            'tipo'            => 'required|string|max:100',
            'color'           => 'required|string|max:100',
            'precio'          => 'required|integer|min:1',
            'stock'           => 'required|integer|min:0',
            'detalles'        => 'nullable|string',
            'codigo_producto' => 'required|string|max:50|unique:camisetas,codigo_producto',
            'tallas'          => 'nullable|array',
            'tallas.*'        => 'exists:tallas,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        $camiseta = Camiseta::create(
            $validator->validated()
        );

        if ($request->has('tallas')) {
            $camiseta->tallas()->sync($request->tallas);
        }

        return $this->successResponse(
            $camiseta->load('tallas'),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: "/camisetas/{camiseta}",
        operationId: "obtenerCamiseta",
        tags: ["Camisetas"],
        summary: "Obtener camiseta",
        description: "Obtiene una camiseta específica y calcula el precio final según el descuento del cliente."
    )]
    #[OA\Parameter(
        name: "camiseta",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Parameter(
        name: "cliente_id",
        in: "query",
        required: false,
        description: "ID del cliente para aplicar descuento",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Camiseta encontrada",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Camiseta"
                )
            ]
        )
    )]
    public function show(Request $request, Camiseta $camiseta): JsonResponse
    {
        $resultado = $camiseta->load('tallas')->toArray();

        $precioFinal = $camiseta->precio;

        if ($request->has('cliente_id')) {

            $cliente = Cliente::find($request->cliente_id);

            if ($cliente && $cliente->porcentaje_oferta > 0) {

                $precioFinal = $camiseta->precio -
                    ($camiseta->precio * $cliente->porcentaje_oferta / 100);
            }
        }

        $resultado['precio_final'] = round($precioFinal);

        return $this->successResponse($resultado);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: "/camisetas/{camiseta}",
        operationId: "actualizarCamiseta",
        tags: ["Camisetas"],
        summary: "Actualizar camiseta",
        description: "Actualiza los datos de una camiseta existente.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/CamisetaInput"
            )
        )
    )]
    #[OA\Parameter(
        name: "camiseta",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Camiseta actualizada correctamente"
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos"
    )]
    public function update(Request $request, Camiseta $camiseta): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'titulo'          => 'required|string|max:255',
            'club'            => 'required|string|max:255',
            'pais'            => 'required|string|max:100',
            'tipo'            => 'required|string|max:100',
            'color'           => 'required|string|max:100',
            'precio'          => 'required|integer|min:1',
            'stock'           => 'required|integer|min:0',
            'detalles'        => 'nullable|string',
            'codigo_producto' => 'required|string|max:50|unique:camisetas,codigo_producto,' . $camiseta->id,
            'tallas'          => 'nullable|array',
            'tallas.*'        => 'exists:tallas,id'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        $camiseta->update(
            $validator->validated()
        );

        if ($request->has('tallas')) {
            $camiseta->tallas()->sync($request->tallas);
        }

        return $this->successResponse(
            $camiseta->load('tallas')
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: "/camisetas/{camiseta}",
        operationId: "eliminarCamiseta",
        tags: ["Camisetas"],
        summary: "Eliminar camiseta",
        description: "Elimina una camiseta siempre que no esté asociada a ventas."
    )]
    #[OA\Parameter(
        name: "camiseta",
        in: "path",
        required: true,
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Camiseta eliminada correctamente"
    )]
    #[OA\Response(
        response: 409,
        description: "La camiseta posee ventas asociadas"
    )]
    public function destroy(Camiseta $camiseta): JsonResponse
    {
        if ($camiseta->detallesVenta()->exists()) {
            return $this->errorResponse(
                'No se puede eliminar una camiseta asociada a ventas.',
                409
            );
        }

        $camiseta->delete();

        return $this->successResponse([
            'mensaje' => 'Camiseta eliminada correctamente.'
        ]);
    }
}
