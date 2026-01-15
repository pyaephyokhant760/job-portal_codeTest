<?php
namespace App\Pipelines\Category;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateCategoryValidation
{
    public function handle($data, Closure $next)
    {
        $validator = Validator::make($data['request']->all(), [
            // $data['category']->id ကို သုံးပြီး လက်ရှိ row ကို ignore လုပ်ခိုင်းပါမယ်
            'name' => 'required|unique:categories,name,' . $data['category']->id,
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($data);
    }
}