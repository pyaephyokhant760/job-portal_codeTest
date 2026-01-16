<?php

namespace App\Pipelines\Application;

use Closure;

class FilterByRole
{
    public function handle($query, Closure $next)
    {
        $user = request()->user();

        if (!$user->hasRole('admin')) {
            $query->whereHas('work', function ($q) use ($user) {
                $q->where('employer_id', $user->id);
            });
        }

        return $next($query);
    }
}