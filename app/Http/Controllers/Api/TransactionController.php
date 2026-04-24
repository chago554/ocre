<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\TransactionException;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    /**
     * Consulta el saldo total del usuario
     *
     * @return JsonResponse
     */
    public function getTotalBalance(): JsonResponse
    {
        try {
            $user = auth()->user();

            $incomes = Transaction::where('user_id', $user->id)
                ->whereHas('category', function ($query) {
                    $query->where('type', 'ingreso');
                })
                ->sum('amount');

            $expenses = Transaction::where('user_id', $user->id)
                ->whereHas('category', function ($query) {
                    $query->where('type', 'gasto');
                })
                ->sum('amount');

            $total = $incomes - $expenses;

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_balance' => number_format((float) $total, 2, '.', ','),
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al calcular el saldo: ' . $th->getMessage());
        }
    }

    /**
     * Consulta el total de ingresos
     *
     * @return JsonResponse
     */
    public function getTotalIncomes(): JsonResponse
    {
        try {

            $user = auth()->user();
            $incomes = Transaction::where('user_id', $user->id)
                ->whereHas('category', function ($query) {
                    $query->where('type', 'ingreso');
                })
                ->sum('amount');


            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_incomes' => number_format((float) $incomes, 2, '.', ','),
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al calcular ingresos total: ' . $th->getMessage());
        }
    }

    /**
     * Consulta el total de gastos
     *
     * @return JsonResponse
     */
    public function getTotalExpenses(): JsonResponse
    {
        try {
            $user = auth()->user();
            $expenses = Transaction::where('user_id', $user->id)
                ->whereHas('category', function ($query) {
                    $query->where('type', 'gasto');
                })
                ->sum('amount');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_expenses' => number_format((float) $expenses, 2, '.', ','),
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al calcular gastos totales: ' . $th->getMessage());
        }
    }

    /**
     * Consultar los ultimos movimientos realizados
     *
     * @return JsonResponse
     */
    public function getLastTransactions(): JsonResponse
    {
        try {
            $user = auth()->user();
            $transactions = Transaction::where('user_id', $user->id)
                ->with('category')
                ->limit(5)
                ->orderBy('created_at', 'desc')
                ->get();

            $data = $transactions->map(function($item){
                return [
                    'amount' => number_format($item->amount, 2, '.', ','),
                    'note' => $item->note ?? '',
                    'name_category' => $item->category->name ?? 'Categoria desconocida',
                    'icon' => $item->category->icon,
                    'type' => $item->category->type,
                    'created_at' => $item->created_at->diffForHumans(),
                ];
            });
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'transactions' => $data,
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al consultar las ultimas transacciones: ' . $th->getMessage());
        }
    }

    /**
     * Consulta las categorias de gastos
     *
     * @return JsonResponse
     */
    public function getCategoriesExpenses(): JsonResponse
    {
        try {
            $categories = Category::where('type', 'gasto')->get();
            $data = $categories->map(function($item){
                return [
                    'id_categoria' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon, 
                    'type' => $item->type, 
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'categories' => $data,
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al consultar las categorias de gastos: ' . $th->getMessage());
        }
    }

    /**
     * Consulta las categorias de gastos
     *
     * @return JsonResponse
     */
    public function getCategoriesIncomes(): JsonResponse
    {
        try {
            $categories = Category::where('type', 'ingreso')->get();
            $data = $categories->map(function($item){
                return [
                    'id_categoria' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon, 
                    'type' => $item->type, 
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'categories' => $data,
                    'currency' => 'MXN'
                ]
            ], 200);

        } catch (\Throwable $th) {
            throw new TransactionException('Error al consultar las categorias de ingresos: ' . $th->getMessage());
        }
    }












    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $results = $request->user()->transactions()->with('category')->get(); 
        // $data = $results->map(function ($item){
        //     'd'
        // });

        return response()->json($results);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
