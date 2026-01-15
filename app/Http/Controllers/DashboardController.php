<?php

namespace App\Http\Controllers;

use App\DTOs\DashboardData;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Pipelines\Dashboard\TotalJobMetrics;
use App\Pipelines\Dashboard\RecentActivities;
use App\Pipelines\Dashboard\TotalUsersByRole;
use App\Pipelines\Dashboard\CVScreeningMetrics;
use App\Pipelines\Dashboard\TotalInterviewMetrics;
use App\Pipelines\Dashboard\TotalApplicationMetrics;

class DashboardController extends Controller
{
    public function index() {
    $dashboard = app(Pipeline::class)
        ->send(new DashboardData())
        ->through([
            TotalUsersByRole::class,
            TotalJobMetrics::class,
            TotalApplicationMetrics::class,
            CVScreeningMetrics::class,
            TotalInterviewMetrics::class,
            RecentActivities::class
        ])
        ->thenReturn();

    return response()->json([
        'success' => true,
        'data' => $dashboard
    ]);
}
}
