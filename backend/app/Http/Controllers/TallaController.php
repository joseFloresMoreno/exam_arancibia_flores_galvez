<?php

namespace App\Http\Controllers;

use App\Models\Talla;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use OpenApi\Attributes as OA;

#[OA\Tag(
    name: "Tallas",
    description: "Gestión de tallas disponibles para camisetas"
)]
class TallaController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: "/tallas",
        operationId: "listarTallas",
        tags: ["Tallas"],
        summary: "Listar tallas",
        description: "Obtiene todas las tallas registradas."
    )]
    #[OA\Response(
        response: 200,
        description: "Listado de tallas",
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
                        ref: "#/components/schemas/Talla"
                    )
                )
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return $this->successResponse(
            Talla::with('camisetas')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: "/tallas",
        operationId: "crearTalla",
        tags: ["Tallas"],
        summary: "Crear talla",
        description: "Registra una nueva talla para camisetas.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/TallaInput"
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Talla creada correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Talla"
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
            'nombre' => 'required|string|max:10|unique:tallas,nombre'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        return $this->successResponse(
            Talla::create($validator->validated()),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: "/tallas/{talla}",
        operationId: "obtenerTalla",
        tags: ["Tallas"],
        summary: "Obtener talla",
        description: "Obtiene una talla específica."
    )]
    #[OA\Parameter(
        name: "talla",
        in: "path",
        required: true,
        description: "ID de la talla",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Talla encontrada",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Talla"
                )
            ]
        )
    )]
    public function show(Talla $talla): JsonResponse
    {
        return $this->successResponse(
            $talla->load('camisetas')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: "/tallas/{talla}",
        operationId: "actualizarTalla",
        tags: ["Tallas"],
        summary: "Actualizar talla",
        description: "Actualiza los datos de una talla existente.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/TallaInput"
            )
        )
    )]
    #[OA\Parameter(
        name: "talla",
        in: "path",
        required: true,
        description: "ID de la talla",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Talla actualizada correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Talla"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos"
    )]
    public function update(Request $request, Talla $talla): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:10|unique:tallas,nombre,' . $talla->id
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        $talla->update(
            $validator->validated()
        );

        return $this->successResponse($talla);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: "/tallas/{talla}",
        operationId: "eliminarTalla",
        tags: ["Tallas"],
        summary: "Eliminar talla",
        description: "Elimina una talla siempre que no esté asociada a camisetas."
    )]
    #[OA\Parameter(
        name: "talla",
        in: "path",
        required: true,
        description: "ID de la talla",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Talla eliminada correctamente"
    )]
    #[OA\Response(
        response: 409,
        description: "La talla está asociada a una o más camisetas"
    )]
    public function destroy(Talla $talla): JsonResponse
    {
        if ($talla->camisetas()->exists()) {
            return $this->errorResponse(
                'No se puede eliminar una talla asociada a camisetas.',
                409
            );
        }

        $talla->delete();

        return $this->successResponse([
            'mensaje' => 'Talla eliminada correctamente.'
        ]);
    }
}
