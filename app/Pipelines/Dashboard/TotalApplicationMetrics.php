<?php

namespace App\Pipelines\Dashboard;

use Closure;
use App\Models\Application;
use App\DTOs\DashboardData;
use Illuminate\Support\Facades\DB;

class TotalApplicationMetrics
{
    public function handle(DashboardData $content, Closure $next)
    {
        
        $metrics = Application::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status') 
            ->toArray();

        $content->applicationMetrics = [
            'applied'     => $metrics['applied'] ?? 0,
            'shortlisted' => $metrics['shortlisted'] ?? 0,
            'rejected'    => $metrics['rejected'] ?? 0,
            'total'       => array_sum($metrics),
        ];

        return $next($content);
    }
}