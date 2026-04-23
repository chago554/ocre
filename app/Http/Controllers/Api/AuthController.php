<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;


#[OA\Info(
    title:"Mi API Laravel",
    version:"1.0.0",
    description:"Documentación de la API"
)]



class AuthController extends Controller
{

    

    #[OA\Post(
        path: "/api/login",
        summary: "Iniciar sesión",
        description: "Autentica un usuario y devuelve un token de acceso (Passport)",
        tags: ["Autenticación"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "usuario@ejemplo.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "12345678")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login exitoso",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string", example: "eyJ0eX..."),
                        new OA\Property(
                            property: "user",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Juan Pérez"),
                                new OA\Property(property: "email", type: "string", format: "email", example: "usuario@ejemplo.com"),
                                new OA\Property(property: "email_verified_at", type: "string", format: "date-time", nullable: true),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Credenciales inválidas",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Credenciales inválidas")
                    ]
                )
            )
        ]
    )]

    public function login(Request $request): JsonResponse
    {
      
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Creamos el token de Passport
            $token = $user->createToken('BreezeToken')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ], 200);
        }

        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }
}
