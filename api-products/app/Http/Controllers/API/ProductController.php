<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     title="API de Productos",
 *     version="1.0.0",
 *     description="Documentación de la API para gestión de productos con Laravel y Sanctum."
 * )
 * @OA\Tag(
 *     name="Productos",
 *     description="Operaciones sobre productos"
 * )
 */
class ProductController extends Controller
{
    // Constructor del controlador que define los middlewares de autenticación y autorización
    public function __construct()
    {
        // Aplica el middleware de autenticación con Laravel Sanctum para todas las rutas del controlador
        $this->middleware('auth:sanctum');

        // Middleware adicional personalizado solo para las acciones: store, update y destroy
        $this->middleware(function ($request, $next) {
            // Verifica si el método actual es uno que requiere permisos de administrador
            if (in_array($request->route()->getActionMethod(), ['store', 'update', 'destroy'])) {
                // Si el usuario no está autenticado o no es admin, se retorna error 403 (Prohibido)
                if (!Auth::user() || !Auth::user()->isAdmin()) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }
            // Si pasa las validaciones, continúa con la ejecución del controlador
            return $next($request);
        })->only(['store', 'update', 'destroy']); // Aplica este middleware solo a esas tres acciones
    }


    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Listar productos",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Listado de productos")
     * )
     */
        // Método que devuelve todos los productos registrados en formato JSON
        public function index(): JsonResponse
        {
            // Obtiene todos los registros de la tabla 'products' usando Eloquent
            $products = Product::all();

            // Retorna los productos como respuesta JSON (por defecto con código 200)
            return response()->json($products);
        }


    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Productos"},
     *     summary="Crear producto (solo admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","description","price","stock"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Producto creado")
     * )
     */
    // Método para crear un nuevo producto (accesible solo para administradores)
    public function store(StoreProductRequest $request): JsonResponse
    {
        // Crea un nuevo producto utilizando los datos validados del formulario
        $product = Product::create($request->validated());

        // Retorna el producto creado como respuesta JSON con código de estado 201 (creado)
        return response()->json($product, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Mostrar producto",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Producto encontrado")
     * )
     */
    // Método para mostrar los detalles de un producto específico
    public function show(Product $product): JsonResponse
    {
        // Retorna el producto recibido como parámetro directamente en formato JSON
        return response()->json($product);
    }


    /**
     * @OA\Put(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Actualizar producto (solo admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Producto actualizado")
     * )
     */
        // Método para actualizar los detalles de un producto específico
        public function update(UpdateProductRequest $request, Product $product): JsonResponse
        {
            // Actualiza el producto con los datos validados proporcionados en la solicitud
            $product->update($request->validated());

            // Retorna el producto actualizado en formato JSON
            return response()->json($product);
        }


    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     tags={"Productos"},
     *     summary="Eliminar producto (solo admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Producto eliminado")
     * )
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
