<?php

namespace App\Pipelines\Interview;
use Closure;

class FilterInterviews
{
    public function handle($query, Closure $next)
    {
        if (auth()->user()->hasRole('recruiter')) {
            $query->where('recruiter_id', auth()->id());
        }
        return $next($query);
    }
}