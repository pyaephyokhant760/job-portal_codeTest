<?php

namespace App\Pipelines\Dashboard;

use Closure;
use App\Models\Work;
use App\Models\Application;
use App\Models\Interview;
use App\DTOs\DashboardData;

class RecentActivities
{
    public function handle(DashboardData $content, Closure $next)
    {
        
        $recentJobs = Work::latest()->take(5)->get(['id', 'title', 'created_at']);

        $recentApplications = Application::with('user:id,name')
            ->latest()
            ->take(5)
            ->get(['id', 'user_id', 'status', 'created_at']);

        $recentInterviews = Interview::with('application.user:id,name')
            ->latest()
            ->take(5)
            ->get(['id', 'application_id', 'outcome', 'date_time']);

        $content->recentActivities = [
            'jobs'         => $recentJobs,
            'applications' => $recentApplications,
            'interviews'   => $recentInterviews,
        ];

        return $next($content);
    }
}