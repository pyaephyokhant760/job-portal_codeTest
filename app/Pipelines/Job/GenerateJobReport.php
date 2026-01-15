<?php


namespace App\Pipelines\Job;

use App\Models\Category;
use Closure;
use Illuminate\Support\Facades\DB;

class GenerateJobReport
{
    public function handle($query, Closure $next)
    {
        // လက်ရှိ Filter မိနေသော Query ကို အခြေခံ၍ Report တွက်ချက်ခြင်း
        $report = [
            'by_category' => Category::withCount(['works' => fn($q) => $q->mergeConstraintsFrom($query)])
                ->get(['id', 'name']),

            'by_status' => (clone $query)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),

            'by_employer' => (clone $query)
                ->join('users', 'works.employer_id', '=', 'users.id')
                ->select('users.name as employer_name', DB::raw('count(*) as total'))
                ->groupBy('users.name')
                ->get(),
            
            'total_jobs' => $query->count()
        ];

        
        return $next([
            'query' => $query,
            'report_data' => $report
        ]);
    }
}