<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'OK', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    public static function error(mixed $errors = null, int $code = 400, ?string $message = null): JsonResponse
    {
        $msg = $message ?? match ($code) {
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            422 => 'Validation Error',
            default => 'Error',
        };

        return response()->json([
            'success' => false,
            'message' => $msg,
            'errors'  => $errors,
        ], $code);
    }

    public static function paginated($paginator): JsonResponse
    {
        return self::success([
            'items' => $paginator->items(),
            'meta'  => [
                'total'        => $paginator->total(),
                'per_page'     => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
            ]
        ]);
    }
}
