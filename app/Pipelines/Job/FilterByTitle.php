<?php
namespace App\Pipelines\Job;

use Closure;
use Illuminate\Http\Request;

class FilterByTitle
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Closure $next
     * @return mixed
     */
    public function handle($payload, Closure $next)
    {
        
        $payload['query']->when(request('title'), function ($query, $title) {
            $query->where('title', 'like', "%{$title}%");
        });

        return $next($payload);
    }
}