<?php

namespace App\Pipelines\Dashboard;

use Closure;
use App\Models\Job;
use App\Models\Application;
use App\DTOs\DashboardData;
use App\Models\Work;
use Illuminate\Support\Facades\DB;

class CVScreeningMetrics
{
    public function handle(DashboardData $content, Closure $next)
    {
        
        $totalJobs = Work::count();
        $totalApplications = Application::count();
        
        $avgCvsPerJob = $totalJobs > 0 ? round($totalApplications / $totalJobs, 1) : 0;

        $skills = ['PHP', 'Laravel', 'Vue', 'React', 'Python'];
        $matchStats = [];

        foreach ($skills as $skill) {
            $matchStats[$skill] = DB::table('applications') 
                ->where('score', 'LIKE', "%{$skill}%")
                ->count();
        }

        $content->ScreeningMetrics = [
            'avg_cvs_per_job' => $avgCvsPerJob,
            'keyword_match_stats' => $matchStats,
        ];

        return $next($content);
    }
}