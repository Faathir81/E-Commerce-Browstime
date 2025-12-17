<?php

namespace App\Http\Controllers;

use App\Services\Midtrans\MidtransWebhookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class MidtransWebhookController extends Controller
{
    public function __construct(private MidtransWebhookService $webhookService)
    {
    }

    public function handle(Request $request): JsonResponse
    {
        $payload = $request->all();

        try {
            $result = $this->webhookService->handle($payload);

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid signature.',
            ], 403);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        } catch (Throwable $e) {
            Log::error('Midtrans webhook processing failed', [
                'error' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed.',
            ], 500);
        }
    }
}
