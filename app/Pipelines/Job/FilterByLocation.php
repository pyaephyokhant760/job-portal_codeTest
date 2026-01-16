<?php

namespace App\Pipelines\Job;

use Closure;

class FilterByLocation
{
    public function handle($payload, Closure $next)
    {
        $payload['query']->when(request('location'), function ($query, $location) {
            return $query->where('location', 'like', "%{$location}%");
        });

        return $next($payload);
    }
}