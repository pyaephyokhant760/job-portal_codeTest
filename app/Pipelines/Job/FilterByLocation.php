<?php

namespace App\Pipelines\Job;

use Closure;

class FilterByLocation
{
    public function handle($query, Closure $next)
    {
        // location ပါလာမှသာ LIKE query စစ်မည်
        $query->when(request('location'), function ($q, $location) {
            $q->where('location', 'like', "%{$location}%");
        });

        return $next($query);
    }
}