<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Services\Shipping\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ShippingController extends Controller
{
    public function __construct(private ShippingService $shipping) {}

    public function provinces(): JsonResponse
    {
        try {
            return $this->ok($this->shipping->getProvinces());
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function cities(int $provinceId): JsonResponse
    {
        try {
            return $this->ok($this->shipping->getCities($provinceId));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), 422);
        }
    }

    public function districts(int $cityId): JsonResponse
    {
        $data = $this->shipping->getDistricts($cityId);
        return $this->ok($data);
    }

    public function estimate(): JsonResponse
    {
        $p = request()->validate([
            'city_id' => ['required','integer','min:1'],
            'weight'  => ['required','integer','min:1'],
        ]);
        $data = $this->shipping->estimateCost($p['city_id'], $p['weight']);
        return $this->ok($data);
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
}
