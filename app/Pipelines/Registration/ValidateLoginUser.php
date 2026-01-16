<?php

namespace App\Pipelines\User;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidateLoginUser
{
    public function handle($command, Closure $next)
    {
        $validator = Validator::make([
            'email'    => $command->email,
            'password' => $command->password,
        ], [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($command);
    }
}