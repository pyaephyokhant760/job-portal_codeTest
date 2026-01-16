<?php

namespace App\Pipelines\Dashboard;

use Closure;
use App\Models\Interview;
use App\DTOs\DashboardData;
use Illuminate\Support\Facades\DB;

class TotalInterviewMetrics
{
    public function handle(DashboardData $content, Closure $next)
    {
       
        $metrics = Interview::select('outcome', DB::raw('count(*) as total'))
            ->groupBy('outcome')
            ->pluck('total', 'outcome')
            ->toArray();

        
        $content->interviewMetrics = [
            'scheduled' => $metrics['scheduled'] ?? 0, 
            'completed' => $metrics['completed'] ?? 0, 
            'pending'   => $metrics['pending'] ?? 0,   
            'passed'    => $metrics['passed'] ?? 0,    
            'failed'    => $metrics['failed'] ?? 0,    
            'total'     => array_sum($metrics)        
        ];

        return $next($content);
    }
}