<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AdminDashboardException;
use App\Http\Controllers\Controller;
use App\Models\DailyMetric;
use App\Models\Post;
use App\Models\Simulation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{

    /**
     * Consulta las cards del dashboard para el admin
     *
     * @return JsonResponse
     */
    public function getStats(): JsonResponse
    {
        try {
            $simulations = Simulation::count();
            $users = User::where('role', 'user')->count();
            $posts = Post::count();

            $data = [
                'simulations' => $simulations,
                'users' => $users,
                'posts' => $posts,
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
            

        } catch (\Throwable $th) {
            throw new AdminDashboardException("Error al consultar cards del dashboard: ". $th->getMessage());
        }

    }

    /**
     * Consulta la data para la grafica de interaccion diaria
     *
     * @return JsonResponse
     */
    public function getDailyInteraction(): JsonResponse
    {
        try {
            $inicio =Carbon::now()->startOfMonth()->format('Y-m-d');
            $fin = Carbon::now()->format('Y-m-d');

            $metrics = DailyMetric::whereBetween('date', [$inicio, $fin])->orderBy('date', 'asc')->get();

            $data = [
                'labels' => $metrics->pluck('date'), 
                'datasets' => [
                    [
                        'label' => 'Usuarios Activos',
                        'data' => $metrics->pluck('active_users'),
                        'borderColor' => '#4ade80', // Verde
                        'backgroundColor' => 'rgba(74, 222, 128, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Transacciones',
                        'data' => $metrics->pluck('total_transactions'),
                        'borderColor' => '#3b82f6', // Azul
                        'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Simulaciones',
                        'data' => $metrics->pluck('total_simulations'),
                        'borderColor' => '#f59e0b', // Ámbar/Naranja
                        'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                        'fill' => true,
                    ]
                ]
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
            
        } catch (\Throwable $th) {
            throw new AdminDashboardException("Error al consultar la interaccion diaria del dashboard: ". $th->getMessage());
        }

    }

    /**
     * Carga los ultimos usuario registrados en el sistema
     *
     * @return JsonResponse
     */
    public function getLastUsersRegister(): JsonResponse
    {
        try {
            $inicio =Carbon::now()->startOfMonth();
            $fin = Carbon::now();
            $users = User::where('role', 'user')->whereBetween('created_at', [$inicio, $fin])->orderBy('created_at', 'desc')->limit(50)->get();
            
            $data = $users->map(function($item) use ($users){
                return [
                    'name' => $item->name,
                    'last_name' => $item->last_name,
                    'email' => $item->email,
                    'created_at' => $item->created_at
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
            

        } catch (\Throwable $th) {
            throw new AdminDashboardException("Error al consultar ultimos usuarios registrados: ". $th->getMessage());
        }
    }

   
}
