<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Payment\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MidtransWebhookController extends Controller
{
    public function __construct(private MidtransService $midtrans) {}

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        try {
            $this->midtrans->handleNotification($payload);
            return response()->json(['message' => 'OK']);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Error', 'errors' => $e->errors()], 422);
        } catch (\Throwable $e) {
            // Jangan bocorkan detail ke client
            return response()->json(['message' => 'Server Error'], 500);
        }
    }
}
