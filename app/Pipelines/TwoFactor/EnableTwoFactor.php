<?php


namespace App\Pipelines\TwoFactor;

use PragmaRX\Google2FA\Google2FA;

class EnableTwoFactor {
    public function handle($user, $next) {
        $google2fa = new Google2FA();

        
        $user->update([
            'two_factor_secret' => $google2fa->generateSecretKey(),
            'two_factor_enabled' => true,
        ]);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );

        $user->qr_code_url = $qrCodeUrl;
        return $next($user);
    }
}

?>