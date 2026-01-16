<?php

namespace App\Pipelines\Job;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateWork
{
    public function handle($command, $next)
    {
        // Validator create
        $validator = Validator::make([
            'employer_id' => $command->employer_id,
            'title'       => $command->title,
            'description' => $command->description,
            'category_id' => $command->category_id,
            'location'    => $command->location,
            'expiry_date' => $command->expiry_date,
        ], [
            'employer_id' => 'required|exists:users,id',
            'title'       => 'required',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
            'location'    => 'required',
            'expiry_date' => 'required|date',
        ]);

        // Fail check
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Pass to next pipe
        return $next($command);
    }
}