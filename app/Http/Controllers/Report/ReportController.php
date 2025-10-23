<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function __construct(private ReportService $report) {}

    public function salesDaily(): JsonResponse
    {
        $p = request()->validate([
            'date_from' => ['required','date','before_or_equal:date_to'],
            'date_to'   => ['required','date','after_or_equal:date_from'],
        ]);
        $data = $this->report->salesDaily($p['date_from'], $p['date_to']);
        return $this->ok($data);
    }

    public function stockCurrent(): JsonResponse
    {
        $data = $this->report->stockCurrent();
        return $this->ok($data);
    }

    public function dashboardSummary(): JsonResponse
    {
        $data = $this->report->summaryForDashboard();
        return $this->ok($data);
    }

    private function ok($data, string $message = 'OK', int $code = 200): JsonResponse
    {
        if (class_exists(\App\Support\ApiResponse::class)) {
            return \App\Support\ApiResponse::success($data, $message, $code);
        }
        return response()->json(['message' => $message, 'data' => $data], $code);
    }
}
