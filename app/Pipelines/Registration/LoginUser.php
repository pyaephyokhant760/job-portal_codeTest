<?php

namespace App\Pipelines\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginUser {
    public function handle($request, $next) {
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Invalid login credentials.'],
            ]);
        }
        return $next(Auth::user());
    }
}
