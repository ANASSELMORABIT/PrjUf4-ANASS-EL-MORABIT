<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Operaciones de autenticación"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="Registrar usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","password_confirmation"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="password_confirmation", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Usuario registrado")
     * )
     */
        // Método para registrar un nuevo usuario y devolver su token de autenticación
        public function register(RegisterRequest $request): JsonResponse
        {
            // Crea un nuevo usuario utilizando los datos validados del formulario
            $user = User::create([
                'name' => $request->name, // Asigna el nombre desde el formulario
                'email' => $request->email, // Asigna el email desde el formulario
                'password' => Hash::make($request->password), // Encripta la contraseña antes de guardarla
                'role' => 'user', // Asigna por defecto el rol de "user"
            ]);

            // Genera un token de acceso para el usuario utilizando Laravel Sanctum
            $token = $user->createToken('auth_token')->plainTextToken;

            // Devuelve una respuesta JSON con los datos del usuario y el token de acceso, con código 201 (creado)
            return response()->json([
                'user' => $user,   // Devuelve la información del nuevo usuario
                'token' => $token, // Devuelve el token de autenticación para que el frontend lo use
            ], 201);
        }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="Login de usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Login exitoso")
     * )
     */
    // Método para autenticar un usuario existente y devolver un token de acceso
    public function login(LoginRequest $request): JsonResponse
    {
        // Busca un usuario por su dirección de correo electrónico
        $user = User::where('email', $request->email)->first();

        // Verifica si el usuario existe y si la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            // Si las credenciales no son válidas, lanza una excepción de validación con un mensaje de error
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Si las credenciales son válidas, genera un nuevo token de acceso para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        // Devuelve una respuesta JSON con la información del usuario, el token y su rol
        return response()->json([
            'user' => $user,      // Objeto del usuario autenticado
            'token' => $token,    // Token de autenticación generado por Sanctum
            'role' => $user->role // Rol del usuario (admin o user)
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout de usuario",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Logout exitoso")
     * )
     */
  // Método para cerrar sesión del usuario autenticado
        public function logout(): JsonResponse
        {
            // Elimina todos los tokens del usuario autenticado (revoca el acceso desde cualquier dispositivo o sesión)
            Auth::user()->tokens()->delete();

            // Devuelve una respuesta JSON indicando que la sesión se ha cerrado correctamente
            return response()->json(['message' => 'Logged out successfully']);
        }

}
