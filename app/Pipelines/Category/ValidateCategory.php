<?php

namespace App\Pipelines\Category;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ValidateCategory
{
    public function handle($command, Closure $next)
    {
        // လက်ရှိ Category ID ရှိလျှင် (Update လုပ်နေလျှင်) ယူထားမည်
        $categoryId = $command->id ?? null;

        $validator = Validator::make([
            'name' => $command->name,
           
        ], [
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($categoryId)],
           
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($command);
    }
}