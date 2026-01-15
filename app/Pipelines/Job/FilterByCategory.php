<?php

namespace App\Pipelines\Job;

use Closure;

class FilterByCategory
{
    public function handle($query, Closure $next)
    {
        // category_id ပါလာမှသာ တိကျသော ID ဖြင့် စစ်မည်
        $query->when(request('category_id'), function ($q, $categoryId) {
            $q->where('category_id', $categoryId);
        });

        return $next($query);
    }
}