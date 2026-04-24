<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class AdminDashboardException extends Exception
{
    protected $code = 422;

    public function render(Request $request)
    {
        return response()->json([
            'status' => 'error',
            'type' => 'ADMIN_DASHBOARD_ERROR',
            'message' => $this->getMessage(),
        ], $this->code);

    }
}
