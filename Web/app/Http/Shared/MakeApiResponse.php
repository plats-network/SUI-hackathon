<?php

namespace App\Http\Shared;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait MakeApiResponse
{
    public function successResponse(string|array $message): JsonResponse
    {
        $data = ['status' => 'success'];

        if (is_string($message)) {
            return response()->json(
                array_merge($data, ['message' => $message])
            );
        }

        return response()->json(
            array_merge($data, $message)
        );
    }

    public function errorResponse(string|array $message, int $code): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }

    //Send Log To Admin
    public function sendLogToAdmin($message, $code = 500)
    {
        Log::error($message);
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
