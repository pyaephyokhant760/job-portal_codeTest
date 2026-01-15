<?php

namespace App\Pipelines\Category;

use Closure;

class UpdateCategory
{
    public function handle($data, Closure $next)
    {
        $category = $data['category'];
        $request = $data['request'];

        $category->update([
            'name' => $request->name
        ]);

        return $next($category);
    }
}