<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvestmentRateException extends Exception
{
    protected $code = 422;

    public function render(Request $request)
    {
        return response()->json([
            'status' => 'error',
            'type' => 'INVESTMENT_RATE_ERROR',
            'message' => $this->getMessage(),
        ], $this->code);

    }
}
