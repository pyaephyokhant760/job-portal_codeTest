<?php

namespace App\Pipelines\Category;

use App\Models\Category;
use Closure;

class StoreCategory
{
    public function handle($request, Closure $next)
    {
        $category = Category::create([
            'name' => $request->name,
            
        ]);

        return $next($category);
    }
}