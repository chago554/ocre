<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class TransactionException extends Exception
{
    
    protected $code = 422;

    public function render(Request $request)
    {
        return response()->json([
            'status' => 'error',
            'type' => 'OCRE_BUSINESS_ERROR',
            'message' => $this->getMessage(),
        ], $this->code);

    }
}
