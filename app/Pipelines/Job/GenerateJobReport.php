<?php


namespace App\Pipelines\Job;

use App\Models\Category;
use Closure;
use Illuminate\Support\Facades\DB;

class GenerateJobReport
{
    public function handle($payload, Closure $next)
    {
        $query = $payload['query'];

        $report = [
            // Filter criteria များအတိုင်း Category အလိုက် Count တွက်ခြင်း
            'by_category' => Category::withCount(['works' => function($q) {
                $q->when(request('title'), fn($query, $title) => $query->where('title', 'like', "%{$title}%"))
                  ->when(request('location'), fn($query, $loc) => $query->where('location', 'like', "%{$loc}%"))
                  ->when(request('category_id'), fn($query, $catId) => $query->where('category_id', $catId));
            }])->get(),

            // Status အလိုက် Count တွက်ခြင်း
            'by_status' => (clone $query)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get(),

            // Employer အလိုက် Count တွက်ခြင်း
            'by_employer' => (clone $query)
                ->join('users', 'works.employer_id', '=', 'users.id')
                ->select('users.name as employer_name', DB::raw('count(*) as total'))
                ->groupBy('users.name')
                ->get(),
            
            'total_jobs' => (clone $query)->count()
        ];

        $payload['report_data'] = $report;
        return $next($payload);
    }
}