<?php

namespace App\Pipelines\Dashboard;

use Closure;
use App\Models\Work;
use App\DTOs\DashboardData;

class TotalJobMetrics
{
    public function handle(DashboardData $content, Closure $next)
    {
        
        $content->jobMetrics = [
            'active'  => Work::where('status', 'active')->count(),
            'expired' => Work::where('expiry_date', '<', now())->count(),
            'closed'  => Work::where('status', 'closed')->count(),
            'total'   => Work::count(), 
        ];

        return $next($content);
    }
}