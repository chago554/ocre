<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class DailyMetricsException extends Exception
{
    protected $code = 422;

    public function render(Request $request)
    {
        return response()->json([
            'status' => 'error',
            'type' => 'DAILY_METRICS_ERROR',
            'message' => $this->getMessage(),
        ], $this->code);

    }
}
