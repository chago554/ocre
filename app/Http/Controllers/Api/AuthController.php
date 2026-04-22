<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * Login
     *
     * @param Request $request
     * @return JsonResponse
     */
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
