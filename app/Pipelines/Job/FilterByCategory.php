<?php

namespace App\Pipelines\Job;

use Closure;

class FilterByCategory
{
    public function handle($payload, Closure $next)
    {
       
        $payload['query']->when(request('category_id'), function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        });

        return $next($payload);
    }
}