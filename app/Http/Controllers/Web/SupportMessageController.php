<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\SupportMessagesException;
use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportMessageController extends Controller
{
    /**
     * Consulta los mensajes 
     *
     * @return void
     */
    public function index()
    {
        return view('buzon.index');
    }

    /**
     * Consulta los mensages de soporte
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getBuzon(Request $request): JsonResponse
    {
        try {
            $query = SupportMessage::with('user');

            $allowed = ['created_at', 'is_resolved', 'updated_at'];
            $sorted  = false;
            foreach ((array) $request->input('sort', []) as $s) {
                if (in_array($s['field'] ?? '', $allowed)) {
                    $query->orderBy($s['field'], ($s['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc');
                    $sorted = true;
                }
            }
            if (!$sorted) {
                $query->latest();
            }

            $size = max(1, min((int) $request->input('size', 15), 100));
            return response()->json($query->paginate($size));

        } catch (\Throwable $th) {
            throw new SupportMessagesException("Error al consultar los mensajes: " . $th->getMessage());
        }
    }

    public function show(SupportMessage $message)
    {
        $message->load('user');
        return view('buzon.show', compact('message'));
    }

    /**
     * Cambia el estado del mensaje
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function toggleResolve(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $message = SupportMessage::where('id', $request->id_message)->first();
            if(!$message){
                return response()->json([
                    'message' => 'No se encontro el mensaje'
                ], 404);
            }
            $current_status = $message->is_resolved;
            $new_status = $current_status == 1 ? 0 : 1;

            $message->update([
                'is_resolved' => $new_status
            ]);
            
            DB::commit();
            return response()->json([
                'message' => "Estado actualizado con exito",
                'is_resolved' => $message->is_resolved

            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new SupportMessagesException("Error el cambiar estado del message");
            
        }
    }

    /**
     * Eliminacion logica del mensaje
     *
     * @param SupportMessage $message
     * @return JsonResponse
     */
    public function destroy(SupportMessage $message): JsonResponse
    {
       DB::beginTransaction();

       try {
        $message = SupportMessage::where('id', $message->id)->first();

        if(!$message){
            return response()->json([
                'message' => 'Mensaje no encontrado.'
            ], 404);
        }

        $message->delete();
        DB::commit();

        return response()->json([
            'message' => 'Mensaje eliminado con exito.'
        ], 200);
       } catch (\Throwable $th) {
        DB::rollBack();
        throw new SupportMessagesException("Error al eliminar mensaje");
       }

    }
}
