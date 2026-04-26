<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\InvestmentRateException;
use App\Http\Controllers\Controller;
use App\Models\InvestmentRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvestmentRateController extends Controller
{
    /**
     * Retorna la vista de tasas de inversion
     *
     * @return View
     */
    public function index(): View
    {
        return view('tasas.index');
    }

    /**
     * Consulta todas las tasas de inversion
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRates(Request $request): JsonResponse
    {
        try {
            $query = InvestmentRate::query();

            $allowed = ['instrument_name', 'annual_rate', 'updated_at'];
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
            throw new InvestmentRateException("Error al consultar las tasas de inversion: " . $th->getMessage());
        }
    }

    /**
     * Consulta una tasa de invesion en especifico
     *
     * @param integer $id_rate
     * @return JsonResponse
     */
    public function getRate(int $id_rate): JsonResponse
    {
        try {
            $data = InvestmentRate::where('id', $id_rate)->first();
            return response()->json($data);
        } catch (\Throwable $th) {
            throw new InvestmentRateException("Error al consultar unsa sola tasa de inversion: " . $th->getMessage());
        }
    }

    /**
     * Crea una nueva tasa de inversion
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $request->validate([
            'instrument_name' => 'required|string|max:255',
            'annual_rate'     => 'required|numeric|min:0|max:999.99',
        ]);

        DB::beginTransaction();
        try {
            InvestmentRate::create([
                'instrument_name' => $request->instrument_name,
                'annual_rate' => $request->annual_rate,
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Tasa creada correctamente.'
            ], 201);

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new InvestmentRateException("Error al crear tasa de inversion");
        }
        
    }
    

    /**
     * Actualiza la informacion de una tasa de inversion
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'instrument_name' => 'required|string|max:255',
            'annual_rate'     => 'required|numeric|min:0|max:999.99',
        ]);

        DB::beginTransaction();
        try {
            $rate = InvestmentRate::where('id', $request->id)->first();

            if(!$rate){
                return response()->json([
                    "message" => "Taza de inversion no encontrada!"
                ], 404);    
            }

            $rate->update([
                'instrument_name' => $request->instrument_name,
                'annual_rate' => $request->annual_rate
            ]);

            DB::commit();
            return response()->json([
                "message" => "Tasa de inversion actualizada con exito!"
            ], 200); 
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new InvestmentRateException("Error al crear tasa de inversion");
        }
    }

    /**
     * Eliminacion logca de una taza de inversion
     *
     * @param InvestmentRate $rate
     * @return void
     */
    public function destroy(InvestmentRate $rate)
    {
        DB::beginTransaction();

       try {
        $rate = InvestmentRate::where('id', $rate->id)->first();

        if(!$rate){
            return response()->json([
                'message' => 'Post no encontrado.'
            ], 404);
        }

        $rate->delete();
        DB::commit();
        return response()->json([
            'message' => 'Tasa eliminada correctamente.'
        ], 200);

       } catch (\Throwable $th) {
        DB::rollBack();
        throw new InvestmentRateException("Error al eliminar taza de inversion");
       }


    }
}
