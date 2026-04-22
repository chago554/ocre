<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Registra un nuevo usuario
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {

            $validator = validator($request->all(), [
                'name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'email' => 'required|unique:users|max:255|email',
                'password' => 'required|max:255|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado correctamente',
                'user' => $user,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al registrar usuario', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al registrar usuario',
            ], 500);
        }
    }
}
