<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Traits\ApiResponse;
use App\Models\DetalleVenta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use OpenApi\Attributes as OA;

#[OA\Tag( name: "Clientes", description: "Gestión de clientes B2B de TodoCamisetas" )]
class ClienteController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: "/clientes",
        operationId: "listarClientes",
        tags: ["Clientes"],
        summary: "Listar clientes",
        description: "Obtiene todos los clientes registrados."
    )]
    #[OA\Response(
        response: 200,
        description: "Listado de clientes",
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
                        ref: "#/components/schemas/Cliente"
                    )
                )
            ]
        )
    )]
    public function index(): JsonResponse
    {
        return $this->successResponse(Cliente::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: "/clientes",
        operationId: "crearCliente",
        tags: ["Clientes"],
        summary: "Crear cliente",
        description: "Registra un nuevo cliente validando el RUT chileno.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/ClienteInput"
            )
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Cliente creado correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Cliente"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos o RUT inválido"
    )]
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre_comercial'  => 'required|string|max:255',
            'rut_comercial'     => 'required|string|max:20|unique:clientes,rut_comercial',
            'direccion'         => 'required|string|max:255',
            'categoria'         => 'required|in:Regular,Preferencial',
            'contacto_nombre'   => 'required|string|max:100',
            'contacto_email'    => 'required|email|max:255',
            'porcentaje_oferta' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        if (!$this->validarRut($request->rut_comercial)) {
            return $this->errorResponse(
                'El RUT ingresado no es válido.',
                422
            );
        }

        return $this->successResponse(
            Cliente::create($validator->validated()),
            201
        );
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: "/clientes/{cliente}",
        operationId: "obtenerCliente",
        tags: ["Clientes"],
        summary: "Obtener cliente",
        description: "Obtiene la información de un cliente específico."
    )]
    #[OA\Parameter(
        name: "cliente",
        in: "path",
        required: true,
        description: "ID del cliente",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Cliente encontrado",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Cliente"
                )
            ]
        )
    )]
    public function show(Cliente $cliente): JsonResponse
    {
        return $this->successResponse($cliente);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: "/clientes/{cliente}",
        operationId: "actualizarCliente",
        tags: ["Clientes"],
        summary: "Actualizar cliente",
        description: "Actualiza los datos de un cliente existente.",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                ref: "#/components/schemas/ClienteInput"
            )
        )
    )]
    #[OA\Parameter(
        name: "cliente",
        in: "path",
        required: true,
        description: "ID del cliente",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Cliente actualizado correctamente",
        content: new OA\JsonContent(
            properties: [
                new OA\Property(
                    property: "success",
                    type: "boolean",
                    example: true
                ),
                new OA\Property(
                    property: "data",
                    ref: "#/components/schemas/Cliente"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: "Datos inválidos o RUT inválido"
    )]
    public function update(Request $request, Cliente $cliente): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre_comercial'  => 'required|string|max:255',
            'rut_comercial'     => 'required|string|max:20|unique:clientes,rut_comercial,' . $cliente->id,
            'direccion'         => 'required|string|max:255',
            'categoria'         => 'required|in:Regular,Preferencial',
            'contacto_nombre'   => 'required|string|max:100',
            'contacto_email'    => 'required|email|max:255',
            'porcentaje_oferta' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(
                'Los datos enviados no son válidos.',
                422,
                $validator->errors()->toArray()
            );
        }

        if (!$this->validarRut($request->rut_comercial)) {
            return $this->errorResponse(
                'El RUT ingresado no es válido.',
                422
            );
        }

        $cliente->update($validator->validated());

        return $this->successResponse($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: "/clientes/{cliente}",
        operationId: "eliminarCliente",
        tags: ["Clientes"],
        summary: "Eliminar cliente",
        description: "Elimina un cliente siempre que no posea ventas asociadas."
    )]
    #[OA\Parameter(
        name: "cliente",
        in: "path",
        required: true,
        description: "ID del cliente",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Cliente eliminado correctamente"
    )]
    #[OA\Response(
        response: 409,
        description: "El cliente posee ventas asociadas"
    )]
    public function destroy(Cliente $cliente): JsonResponse
    {
        if ($cliente->ventas()->exists()) {
            return $this->errorResponse(
                'No se puede eliminar un cliente con ventas asociadas.',
                409
            );
        }

        $cliente->delete();

        return $this->successResponse([
            'mensaje' => 'Cliente eliminado correctamente.'
        ]);
    }

    /**
     * Valida un RUT chileno.
     */
    private function validarRut(string $rut): bool
    {
        $rut = strtoupper(trim($rut));
        $rut = preg_replace('/[^0-9K]/', '', $rut);

        $dv = substr($rut, -1);
        $numero = substr($rut, 0, -1);

        if (!is_numeric($numero)) {
            return false;
        }

        $factor = 2;
        $suma = 0;

        for ($i = strlen($numero) - 1; $i >= 0; $i--) {
            $suma += intval($numero[$i]) * $factor;
            $factor = ($factor == 7) ? 2 : $factor + 1;
        }

        $resto = 11 - ($suma % 11);

        if ($resto == 11) {
            $dvEsperado = '0';
        } elseif ($resto == 10) {
            $dvEsperado = 'K';
        } else {
            $dvEsperado = (string) $resto;
        }

        return $dvEsperado === $dv;
    }

    /**
     * Lista las camisetas compradas por un cliente.
     */
    #[OA\Get(
        path: "/clientes/{cliente}/camisetas",
        operationId: "camisetasCliente",
        tags: ["Clientes"],
        summary: "Listar camisetas compradas por un cliente",
        description: "Obtiene todas las camisetas adquiridas por un cliente."
    )]
    #[OA\Parameter(
        name: "cliente",
        in: "path",
        required: true,
        description: "ID del cliente",
        schema: new OA\Schema(type: "integer")
    )]
    #[OA\Response(
        response: 200,
        description: "Listado de camisetas compradas",
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
    public function camisetas(Cliente $cliente): JsonResponse
    {
        $camisetas = DetalleVenta::with('camiseta')
            ->whereHas('venta', function ($query) use ($cliente) {
                $query->where('cliente_id', $cliente->id);
            })
            ->get()
            ->pluck('camiseta');

        return $this->successResponse($camisetas);
    }
}
