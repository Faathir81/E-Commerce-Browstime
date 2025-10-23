<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\PaymentCreateRequest;
use App\Http\Requests\Payment\PaymentProofUploadRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Payment\PaymentService;
use App\Services\Payment\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $payments,
        private MidtransService $midtrans
    ) {}

    /**
     * Buat payment intent:
     * - manual: transfer/qris (upload bukti nanti)
     * - midtrans: qris/bank_transfer/gopay (charge langsung)
     */
    public function create(PaymentCreateRequest $request): JsonResponse
    {
        $data  = $request->validated();
        $order = Order::where('code', $data['order_code'])->firstOrFail();
        $amount = (int) ($data['amount'] ?? ($order->total ?? 0));

        try {
            if ($data['provider'] === 'manual') {
                $p = $this->payments->createManual($order, $amount, $data['method']);
                return $this->ok([
                    'payment_id' => $p->id,
                    'method'     => $p->method,
                    'provider'   => 'manual',
                    'status'     => $p->status,
                ], 'Manual payment created', 201);
            }

            // Midtrans
            $channel = $data['method']; // 'qris'|'bank_transfer'|'gopay'
            $charge  = $this->midtrans->createCharge($order, $amount, $channel);

            return $this->ok([
                'provider'   => 'midtrans',
                'channel'    => $channel,
                'charge'     => $charge,
            ], 'Midtrans charge created', 201);

        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Upload bukti bayar untuk manual payment.
     */
    public function uploadProof(PaymentProofUploadRequest $request, int $paymentId): JsonResponse
    {
        $payment = Payment::findOrFail($paymentId);

        try {
            $p = $this->payments->attachProof($payment, $request->file('proof'));
            return $this->ok([
                'payment_id' => $p->id,
                'status'     => $p->status,
                'proof_url'  => $this->toPublicUrl($p->proof_url),
            ], 'Proof uploaded');
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    /**
     * Cek status payment by order code (atau by payment id via route lain kalau perlu).
     */
    public function status(string $orderCode): JsonResponse
    {
        $order = Order::where('code', $orderCode)->first();
        if (! $order) return $this->notFound();

        $p = Payment::where('order_id', $order->id)->latest('id')->first();
        if (! $p) {
            return $this->ok(['status' => 'none'], 'No payment found');
        }

        return $this->ok([
            'payment_id'   => $p->id,
            'provider'     => $p->provider,
            'method'       => $p->method,
            'status'       => $p->status,
            'amount'       => (int)$p->amount,
            'proof_url'    => $this->toPublicUrl($p->proof_url),
            'provider_ref' => $p->provider_ref,
        ]);
    }

    private function ok($data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::success($data, $message, $code);
        }
        return response()->json(['message' => $message, 'data' => $data], $code);
    }

    private function error($errors, int $code = 400): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::error($errors, $code);
        }
        return response()->json(['message' => 'Error', 'errors' => $errors], $code);
    }

    private function notFound(): JsonResponse
    {
        return response()->json(['message' => 'Not Found'], 404);
    }

    private function toPublicUrl(?string $path): ?string
    {
        if (! $path) return null;
        return str_contains($path, 'http')
            ? $path
            : asset('storage/' . ltrim($path, '/'));
    }
}
