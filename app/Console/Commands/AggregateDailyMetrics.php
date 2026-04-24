<?php

namespace App\Console\Commands;

use App\Exceptions\DailyMetricsException;
use App\Models\DailyMetric;
use App\Models\Simulation;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class AggregateDailyMetrics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:aggregate-daily-metrics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Contar usuarios activos, suma de transacciones y simulaciones del día anterior';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $inicioDia = Carbon::today()->startOfDay();
        $finDia    = Carbon::today()->endOfDay();
        
        // Conteo de registros
        $users = User::whereBetween('created_at', [$inicioDia, $finDia])->count();
        $transacciones = Transaction::whereBetween('created_at', [$inicioDia, $finDia])->count();
        $simulations = Simulation::whereBetween('created_at', [$inicioDia, $finDia])->count();

        $data = [
            'date' =>  date('Y-m-d'),
            'active_users' => $users,
            'total_transactions' => $transacciones,
            'total_simulations' => $simulations,
        ];

        DB::beginTransaction();
        try{
            $metric = DailyMetric::updateOrCreate(
                ['date' => Carbon::today()->toDateString()],
                [
                    'active_users'       => $users,
                    'total_transactions' => $transacciones,
                    'total_simulations'  => $simulations,
                    'update_at' => Carbon::now(),
                ]
            );
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new DailyMetricsException('Error al generar conteo de metricas: ' . $th->getMessage());
        }

        return Command::SUCCESS;
    }
}
