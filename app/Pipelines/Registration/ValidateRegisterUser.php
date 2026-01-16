<?php
namespace App\Pipelines\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class ValidateRegisterUser
{
    public function handle($command, $next)
    {
        $validator = Validator::make([
            'name'     => $command->name,
            'email'    => $command->email,
            'password' => $command->password,
            'role'     => $command->role,
        ], [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'], 
            'password' => ['required', 'min:8'], 
            'role'     => ['required', Rule::in(['admin', 'recruiter', 'employer','job_seeker'])], 
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($command);
    }
}
