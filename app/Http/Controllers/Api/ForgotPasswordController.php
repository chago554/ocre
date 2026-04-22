<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SendResetCodeApiMail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Enviar codigo para reestablecer contraseña
     */
    public function sendResetCode(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        DB::beginTransaction();

        try {
            $user = User::where('email', $request->email)->first();
            $code = mt_rand(100000, 999999);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $code,
                    'created_at' => now(),
                ]
            );

            $data = [
                'code' => $code,
                'name' => $user->name ?? ', estimado',
            ];

            DB::commit();
            Mail::to($request->email)->send(new SendResetCodeApiMail($data));

            return response()->json([
                'message' => 'Se ha enviado un código de verificación a tu correo, revisalo y sigue las instrucciones',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al enviar codigo para reestablecer tu contraseña', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al enviar codigo para reestablecer tu contraseña',
            ], 500);

        }
    }

    /**
     * Cambio de contraseña
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string',
            'password' => 'required|min:8',
        ]);

        DB::beginTransaction();

        try {
            $record = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->code)
                ->first();

            if (! $record || now()->subMinutes(60)->gt($record->created_at)) {
                return response()->json([
                    'message' => 'El código es inválido o ha expirado.',
                ], 422);
            }

            User::where('email', $request->email)
                ->first()
                ->update([
                    'password' => bcrypt($request->password),
                ]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::commit();

            return response()->json([
                'message' => 'Contraseña actualizada con éxito.',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al reestablecer tu contraseña', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Error al reestablecer tu contraseña',
            ], 500);
        }
    }
}
