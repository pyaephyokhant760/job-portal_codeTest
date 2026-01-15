<?php

namespace App\Pipelines\Registration;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\ValidationException;
class VerifyTwoFactor
{
    public function handle($request, $next) {
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user && $user->two_factor_enabled) {
            $google2fa = new Google2FA();
            $valid = $google2fa->verifyKey($user->two_factor_secret, $request->two_factor_code);

            if (!$valid) {
                throw ValidationException::withMessages([
                    'two_factor_code' => ['The 2FA code is incorrect.'],
                ]);
            }
        }

        return $next($request);
    }
}
